<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * payments: mỗi lần thu / hoàn tiền là một bản ghi riêng.
 * Không đánh dấu "đã thanh toán" chỉ vì đã tạo đơn.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('shift_session_id')->nullable()->constrained('shift_sessions')->nullOnDelete();
            $table->foreignId('store_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('type', ['payment', 'refund'])->default('payment');
            $table->string('method', 20); // cash|card|bank_transfer|ewallet|cod|vnpay
            $table->decimal('amount', 15, 2);
            $table->decimal('received_amount', 15, 2)->nullable(); // Khách đưa (tiền mặt)
            $table->decimal('change_amount', 15, 2)->nullable();   // Tiền thối
            $table->enum('status', ['pending', 'success', 'failed', 'cancelled'])->default('pending');
            $table->string('reference', 100)->nullable();          // Mã giao dịch ngân hàng/cổng TT
            $table->text('note')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['shift_session_id', 'status']);
            $table->index(['status', 'paid_at']);
        });

        // Ghi nhận thanh toán cho đơn online cũ đã hoàn thành (để báo cáo nhất quán)
        $now = now();
        DB::table('orders')->where('status', 'completed')->orderBy('id')->chunk(500, function ($orders) use ($now) {
            $rows = [];
            foreach ($orders as $o) {
                $rows[] = [
                    'order_id' => $o->id,
                    'type' => 'payment',
                    'method' => strtolower($o->payment_method ?: 'cod'),
                    'amount' => $o->total,
                    'status' => 'success',
                    'note' => 'Đồng bộ dữ liệu cũ',
                    'paid_at' => $o->updated_at,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            if ($rows) {
                DB::table('payments')->insert($rows);
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

