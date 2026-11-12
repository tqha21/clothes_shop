<?php

namespace App\Services;

use App\Exceptions\BusinessException;
use App\Models\ActivityLog;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * Nghiệp vụ vòng đời đơn hàng dùng chung cho cả hai kênh.
 */
class OrderService
{
    public function __construct(protected StockService $stock)
    {
    }

    /** Sinh mã đơn: ON/POS + yymmdd + id */
    public function assignCode(Order $order): void
    {
        if ($order->code) {
            return;
        }
        $prefix = $order->channel === Order::CHANNEL_POS ? 'POS' : 'ON';
        $order->forceFill([
            'code' => $prefix . now()->format('ymd') . str_pad((string) $order->id, 5, '0', STR_PAD_LEFT),
        ])->save();
    }

    /**
     * Hủy đơn: hoàn kho đúng 1 lần (BR-05), giảm sold_count, xử lý thanh toán.
     * An toàn khi gọi nhiều lần / đồng thời (khóa dòng đơn hàng).
     */
    public function cancel(Order $order, ?User $actor, string $reason = 'Hủy đơn'): Order
    {
        return DB::transaction(function () use ($order, $actor, $reason) {
            /** @var Order $locked */
            $locked = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();

            if ($locked->status === 'cancelled') {
                return $locked; // Đã hủy trước đó -> không hoàn kho lần 2
            }
            if ($locked->status === 'refunded') {
                throw new BusinessException('Đơn đã hoàn tiền toàn bộ, không thể hủy.');
            }

            $restored = $this->stock->restoreForOrder($locked, $actor?->id, $reason);

            if ($restored) {
                $locked->loadMissing('items');
                foreach ($locked->items as $item) {
                    $left = (int) $item->quantity - (int) $item->refunded_quantity;
                    if ($left > 0 && $item->product_id) {
                        Product::whereKey($item->product_id)
                            ->where('sold_count', '>=', $left)
                            ->decrement('sold_count', $left);
                    }
                }
            }

            // Thanh toán đang chờ -> hủy; đã thu tiền -> ghi nhận hoàn tiền
            Payment::where('order_id', $locked->id)->where('status', 'pending')
                ->update(['status' => 'cancelled', 'updated_at' => now()]);

            $paid = (float) Payment::where('order_id', $locked->id)->where('type', 'payment')->where('status', 'success')->sum('amount');
            $refunded = (float) Payment::where('order_id', $locked->id)->where('type', 'refund')->where('status', 'success')->sum('amount');
            $outstanding = round($paid - $refunded, 2);

            $paymentStatus = $locked->payment_status === 'pending' ? 'failed' : $locked->payment_status;
            if ($outstanding > 0) {
                Payment::create([
                    'order_id' => $locked->id,
                    'store_id' => $locked->store_id,
                    'type' => 'refund',
                    'method' => strtolower($locked->payment_method ?: 'cash'),
                    'amount' => $outstanding,
                    'status' => 'success',
                    'note' => 'Hoàn tiền do hủy đơn: ' . $reason,
                    'processed_by' => $actor?->id,
                    'paid_at' => now(),
                ]);
                $locked->refunded_amount = (float) $locked->refunded_amount + $outstanding;
                $paymentStatus = 'refunded';
            }

            $locked->forceFill([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'payment_status' => $paymentStatus,
            ])->save();

            $this->log($locked, $actor, 'Hủy đơn hàng: ' . $reason);

            return $locked;
        });
    }

    /** Hoàn tất đơn online (đã giao / đã thu COD) -> ghi nhận doanh thu */
    public function markCompleted(Order $order, ?User $actor): Order
    {
        return DB::transaction(function () use ($order, $actor) {
            $locked = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();
            if (in_array($locked->status, ['cancelled', 'refunded'], true)) {
                throw new BusinessException('Không thể hoàn tất đơn đã hủy / đã hoàn tiền.');
            }
            if ($locked->status === 'completed') {
                return $locked;
            }

            $hasPaid = Payment::where('order_id', $locked->id)->where('type', 'payment')->where('status', 'success')->exists();
            if (!$hasPaid) {
                // Đơn online giao thành công: tiền COD/chuyển khoản đã thu
                Payment::where('order_id', $locked->id)->where('status', 'pending')->update([
                    'status' => 'success', 'paid_at' => now(), 'processed_by' => $actor?->id, 'updated_at' => now(),
                ]);
                if (!Payment::where('order_id', $locked->id)->where('type', 'payment')->where('status', 'success')->exists()) {
                    Payment::create([
                        'order_id' => $locked->id,
                        'store_id' => $locked->store_id,
                        'type' => 'payment',
                        'method' => strtolower($locked->payment_method ?: 'cod'),
                        'amount' => $locked->total,
                        'status' => 'success',
                        'processed_by' => $actor?->id,
                        'paid_at' => now(),
                    ]);
                }
            }

            $locked->forceFill([
                'status' => 'completed',
                'completed_at' => now(),
                'payment_status' => 'paid',
            ])->save();

            $this->log($locked, $actor, 'Hoàn tất đơn hàng');
            return $locked;
        });
    }

    public function updateStatus(Order $order, string $status, ?User $actor): Order
    {
        if ($order->status === 'cancelled' || $order->status === 'refunded') {
            throw new BusinessException('Đơn đã hủy / đã hoàn tiền không thể đổi trạng thái (tránh trừ kho lại).');
        }

        return match ($status) {
            'cancelled' => $this->cancel($order, $actor, 'Quản trị viên hủy đơn'),
            'completed' => $this->markCompleted($order, $actor),
            default => tap($order, function ($o) use ($status, $actor) {
                $o->update(['status' => $status]);
                $this->log($o, $actor, 'Cập nhật trạng thái đơn hàng thành: ' . $status);
            }),
        };
    }

    public function log(Order $order, ?User $actor, string $description): void
    {
        try {
            ActivityLog::create([
                'description' => $description,
                'causer_id' => $actor?->id,
                'subject_type' => Order::class,
                'subject_id' => $order->id,
            ]);
        } catch (\Throwable $e) {
            // Không để lỗi ghi log làm hỏng giao dịch chính
            report($e);
        }
    }
}

