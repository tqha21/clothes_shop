<?php

namespace App\Services;

use App\Exceptions\BusinessException;
use App\Exceptions\InsufficientStockException;
use App\Models\InventoryLog;
use App\Models\Order;
use App\Models\Product;
use App\Models\StockTransfer;
use App\Models\Store;
use App\Models\StoreStock;
use Illuminate\Support\Facades\DB;

/**
 * NGUá»’N DUY NHáº¤T thay Ä‘á»•i tá»“n kho cá»§a há»‡ thá»‘ng (Online, POS, Admin).
 *
 * NguyÃªn táº¯c:
 *  - store_stocks lÃ  dá»¯ liá»‡u gá»‘c; products.stock = SUM(store_stocks.quantity) vÃ  Ä‘Æ°á»£c Ä‘á»“ng bá»™ sau má»—i biáº¿n Ä‘á»™ng.
 *  - Má»i thao tÃ¡c cháº¡y trong transaction vÃ  khÃ³a dÃ²ng (SELECT ... FOR UPDATE) Ä‘á»ƒ hai giao dá»‹ch Ä‘á»“ng thá»i
 *    khÃ´ng thá»ƒ cÃ¹ng bÃ¡n sá»‘ lÆ°á»£ng cuá»‘i cÃ¹ng (BR-04, TC-03).
 *  - KhÃ³a theo thá»© tá»± product_id tÄƒng dáº§n Ä‘á»ƒ trÃ¡nh deadlock.
 *  - Má»i biáº¿n Ä‘á»™ng Ä‘á»u ghi inventory_logs vá»›i cá»­a hÃ ng, Ä‘Æ¡n hÃ ng, nguá»“n, ngÆ°á»i thá»±c hiá»‡n, tá»“n sau (BR-08).
 */
class StockService
{
    /* =========================================================
     |  Truy váº¥n
     * ========================================================= */

    public function defaultStoreId(): int
    {
        $store = Store::defaultWarehouse();
        if (!$store) {
            throw new BusinessException('Há»‡ thá»‘ng chÆ°a cÃ³ cá»­a hÃ ng/kho nÃ o. Vui lÃ²ng táº¡o cá»­a hÃ ng trÆ°á»›c.');
        }
        return $store->id;
    }

    public function availableAt(int $storeId, int $productId): int
    {
        $this->ensureProductInitialized($productId);
        return (int) StoreStock::where('store_id', $storeId)->where('product_id', $productId)->value('quantity');
    }

    public function totalAvailable(int $productId): int
    {
        $this->ensureProductInitialized($productId);
        return (int) StoreStock::where('product_id', $productId)->sum('quantity');
    }

    /* =========================================================
     |  Nghiá»‡p vá»¥ kho (Admin / Manager)
     * ========================================================= */

    public function import(int $storeId, int $productId, int $qty, ?int $userId, ?string $note = null): InventoryLog
    {
        $this->assertPositive($qty);
        return DB::transaction(function () use ($storeId, $productId, $qty, $userId, $note) {
            $row = $this->lockRow($storeId, $productId);
            return $this->applyChange($row, $qty, 'import', [
                'user_id' => $userId, 'source' => 'admin', 'note' => $note,
            ]);
        });
    }

    public function export(int $storeId, int $productId, int $qty, ?int $userId, ?string $note = null): InventoryLog
    {
        $this->assertPositive($qty);
        return DB::transaction(function () use ($storeId, $productId, $qty, $userId, $note) {
            $row = $this->lockRow($storeId, $productId);
            return $this->applyChange($row, -$qty, 'export', [
                'user_id' => $userId, 'source' => 'admin', 'note' => $note,
            ]);
        });
    }

    /** Äiá»u chá»‰nh / kiá»ƒm kÃª: Ä‘áº·t tá»“n thá»±c táº¿ = $newQty */
    public function adjust(int $storeId, int $productId, int $newQty, ?int $userId, ?string $note = null, string $type = 'adjustment'): ?InventoryLog
    {
        if ($newQty < 0) {
            throw new BusinessException('Sá»‘ lÆ°á»£ng tá»“n khÃ´ng Ä‘Æ°á»£c Ã¢m.');
        }
        return DB::transaction(function () use ($storeId, $productId, $newQty, $userId, $note, $type) {
            $row = $this->lockRow($storeId, $productId);
            $delta = $newQty - (int) $row->quantity;
            if ($type === 'stocktake') {
                $row->last_counted_at = now();
            }
            if ($delta === 0) {
                $row->save();
                return null; // KhÃ´ng chÃªnh lá»‡ch -> khÃ´ng ghi log biáº¿n Ä‘á»™ng
            }
            return $this->applyChange($row, $delta, $type, [
                'user_id' => $userId, 'source' => 'admin', 'note' => $note,
            ]);
        });
    }

    public function transfer(int $fromStoreId, int $toStoreId, int $productId, int $qty, ?int $userId, ?string $note = null): StockTransfer
    {
        $this->assertPositive($qty);
        if ($fromStoreId === $toStoreId) {
            throw new BusinessException('Cá»­a hÃ ng chuyá»ƒn vÃ  nháº­n pháº£i khÃ¡c nhau.');
        }

        return DB::transaction(function () use ($fromStoreId, $toStoreId, $productId, $qty, $userId, $note) {
            // KhÃ³a theo thá»© tá»± store_id Ä‘á»ƒ trÃ¡nh deadlock giá»¯a hai lá»‡nh chuyá»ƒn ngÆ°á»£c chiá»u
            $ids = [$fromStoreId, $toStoreId];
            sort($ids);
            $rows = [];
            foreach ($ids as $sid) {
                $rows[$sid] = $this->lockRow($sid, $productId);
            }

            $transfer = StockTransfer::create([
                'code' => 'CK' . now()->format('ymdHis') . random_int(10, 99),
                'from_store_id' => $fromStoreId,
                'to_store_id' => $toStoreId,
                'product_id' => $productId,
                'quantity' => $qty,
                'status' => 'completed',
                'user_id' => $userId,
                'note' => $note,
            ]);

            $ctx = [
                'user_id' => $userId, 'source' => 'transfer', 'note' => $note,
                'reference_type' => 'stock_transfer', 'reference_id' => $transfer->id,
            ];
            $this->applyChange($rows[$fromStoreId], -$qty, 'transfer_out', $ctx + ['note' => "Chuyá»ƒn {$transfer->code}" . ($note ? " - $note" : '')]);
            $this->applyChange($rows[$toStoreId], $qty, 'transfer_in', $ctx + ['note' => "Nháº­n {$transfer->code}" . ($note ? " - $note" : '')]);

            return $transfer;
        });
    }

    /**
     * Äá»“ng bá»™ khi Admin sá»­a trá»±c tiáº¿p Ã´ "tá»“n kho" trÃªn form sáº£n pháº©m.
     * ChÃªnh lá»‡ch Ä‘Æ°á»£c Ã¡p vÃ o kho máº·c Ä‘á»‹nh (náº¿u giáº£m vÆ°á»£t quÃ¡ thÃ¬ trá»« tiáº¿p cÃ¡c kho cÃ²n hÃ ng).
     */
    public function reconcileProductTotal(int $productId, int $targetTotal, ?int $userId, string $note = 'Cáº­p nháº­t tá»« form sáº£n pháº©m'): void
    {
        if ($targetTotal < 0) {
            throw new BusinessException('Tá»“n kho khÃ´ng Ä‘Æ°á»£c Ã¢m.');
        }
        DB::transaction(function () use ($productId, $targetTotal, $userId, $note) {
            $this->ensureProductInitialized($productId);
            $defaultId = $this->defaultStoreId();
            $this->lockRow($defaultId, $productId); // Ä‘áº£m báº£o cÃ³ dÃ²ng kho máº·c Ä‘á»‹nh

            $rows = StoreStock::where('product_id', $productId)->orderBy('id')->lockForUpdate()->get();
            $current = (int) $rows->sum('quantity');
            $delta = $targetTotal - $current;
            if ($delta === 0) {
                $this->syncProductTotal($productId);
                return;
            }
            $ctx = ['user_id' => $userId, 'source' => 'admin', 'note' => $note];

            if ($delta > 0) {
                $this->applyChange($rows->firstWhere('store_id', $defaultId), $delta, 'adjustment', $ctx);
                return;
            }

            $remaining = -$delta;
            $ordered = $this->allocationOrder($rows, $defaultId);
            foreach ($ordered as $row) {
                if ($remaining <= 0) break;
                $take = min($remaining, (int) $row->quantity);
                if ($take > 0) {
                    $this->applyChange($row, -$take, 'adjustment', $ctx);
                    $remaining -= $take;
                }
            }
        });
    }

    /* =========================================================
     |  BÃ¡n hÃ ng (dÃ¹ng chung cho Online & POS)
     * ========================================================= */

    /**
     * POS: trá»« kho táº¡i Ä‘Ãºng cá»­a hÃ ng cá»§a Ä‘Æ¡n.
     * @param array<int,int> $quantities [product_id => qty]
     */
    public function deductForPosOrder(Order $order, array $quantities, ?int $userId): void
    {
        if (!$order->store_id) {
            throw new BusinessException('ÄÆ¡n POS pháº£i xÃ¡c Ä‘á»‹nh cá»­a hÃ ng.');
        }
        DB::transaction(function () use ($order, $quantities, $userId) {
            ksort($quantities);
            foreach ($quantities as $productId => $qty) {
                $row = $this->lockRow($order->store_id, (int) $productId);
                $this->applyChange($row, -(int) $qty, 'sale', [
                    'user_id' => $userId, 'order_id' => $order->id, 'source' => 'pos',
                    'note' => "BÃ¡n táº¡i quáº§y ({$order->display_code})",
                ]);
            }
            $order->forceFill(['stock_deducted' => true])->save();
        });
    }

    /**
     * Online: tá»“n kháº£ dá»¥ng = tá»•ng cÃ¡c cá»­a hÃ ng. PhÃ¢n bá»• Æ°u tiÃªn kho máº·c Ä‘á»‹nh, sau Ä‘Ã³ kho cÃ²n nhiá»u hÃ ng nháº¥t.
     * Ghi láº¡i phÃ¢n bá»• theo cá»­a hÃ ng Ä‘á»ƒ há»§y Ä‘Æ¡n hoÃ n Ä‘Ãºng nÆ¡i Ä‘Ã£ trá»«.
     * @param array<int,int> $quantities [product_id => qty]
     */
    public function deductForOnlineOrder(Order $order, array $quantities, ?int $userId): void
    {
        DB::transaction(function () use ($order, $quantities, $userId) {
            ksort($quantities);
            $defaultId = $this->defaultStoreId();

            foreach ($quantities as $productId => $qty) {
                $productId = (int) $productId;
                $qty = (int) $qty;
                $this->ensureProductInitialized($productId);

                $rows = StoreStock::where('product_id', $productId)
                    ->orderBy('id')->lockForUpdate()->get();
                $available = (int) $rows->sum('quantity');

                if ($available < $qty) {
                    $name = Product::whereKey($productId)->value('name') ?? ('#' . $productId);
                    throw new InsufficientStockException($productId, $name, $qty, $available);
                }

                $remaining = $qty;
                $ordered = $this->allocationOrder($rows, $defaultId);
                foreach ($ordered as $row) {
                    if ($remaining <= 0) break;
                    $take = min($remaining, (int) $row->quantity);
                    if ($take <= 0) continue;
                    $this->applyChange($row, -$take, 'sale', [
                        'user_id' => $userId, 'order_id' => $order->id, 'source' => 'online',
                        'note' => "BÃ¡n online ({$order->display_code})",
                    ]);
                    $remaining -= $take;
                }
            }
            $order->forceFill(['stock_deducted' => true])->save();
        });
    }

    /** KhÃ¡ch tráº£ hÃ ng (hoÃ n tiá»n POS): nháº­p láº¡i Ä‘Ãºng cá»­a hÃ ng cá»§a Ä‘Æ¡n */
    public function returnForOrder(Order $order, int $productId, int $qty, ?int $userId, ?string $note = null): InventoryLog
    {
        $this->assertPositive($qty);
        return DB::transaction(function () use ($order, $productId, $qty, $userId, $note) {
            $storeId = $order->store_id ?: $this->defaultStoreId();
            $row = $this->lockRow($storeId, $productId);
            return $this->applyChange($row, $qty, 'return', [
                'user_id' => $userId, 'order_id' => $order->id, 'source' => $order->channel ?? 'pos',
                'note' => $note ?: "KhÃ¡ch tráº£ hÃ ng ({$order->display_code})",
            ]);
        });
    }

    /**
     * HoÃ n kho khi há»§y Ä‘Æ¡n â€” CHá»ˆ hoÃ n náº¿u Ä‘Æ¡n Ä‘Ã£ trá»« kho vÃ  chÆ°a hoÃ n trÆ°á»›c Ä‘Ã³ (BR-05).
     * Gá»i bÃªn trong transaction Ä‘Ã£ khÃ³a dÃ²ng Ä‘Æ¡n hÃ ng (OrderService::cancel).
     */
    public function restoreForOrder(Order $order, ?int $userId, string $reason = 'Há»§y Ä‘Æ¡n'): bool
    {
        if (!$order->stock_deducted || $order->stock_restored_at) {
            return false;
        }

        return DB::transaction(function () use ($order, $userId, $reason) {
            // Sá»‘ lÆ°á»£ng cÃ²n "Ä‘ang trá»«" theo tá»«ng (cá»­a hÃ ng, sáº£n pháº©m) = sale (Ã¢m) + return (dÆ°Æ¡ng)
            $net = InventoryLog::where('order_id', $order->id)
                ->whereIn('type', ['sale', 'return'])
                ->selectRaw('store_id, product_id, SUM(quantity_changed) as net_qty')
                ->groupBy('store_id', 'product_id')
                ->get()
                ->filter(fn ($r) => (int) $r->net_qty < 0)
                ->sortBy('product_id');

            $ctx = [
                'user_id' => $userId, 'order_id' => $order->id, 'source' => $order->channel ?? 'online',
                'note' => "{$reason} ({$order->display_code})",
            ];

            if ($net->isNotEmpty()) {
                foreach ($net as $r) {
                    $row = $this->lockRow((int) $r->store_id, (int) $r->product_id);
                    $this->applyChange($row, -(int) $r->net_qty, 'cancel_restore', $ctx);
                }
            } else {
                // ÄÆ¡n cÅ© (trÆ°á»›c khi cÃ³ truy váº¿t theo Ä‘Æ¡n): hoÃ n vá» kho máº·c Ä‘á»‹nh theo sá»‘ lÆ°á»£ng cÃ²n láº¡i trÃªn Ä‘Æ¡n
                $defaultId = $this->defaultStoreId();
                $order->loadMissing('items');
                $qtyByProduct = [];
                foreach ($order->items as $item) {
                    $left = (int) $item->quantity - (int) $item->refunded_quantity;
                    if ($left > 0 && $item->product_id) {
                        $qtyByProduct[$item->product_id] = ($qtyByProduct[$item->product_id] ?? 0) + $left;
                    }
                }
                ksort($qtyByProduct);
                foreach ($qtyByProduct as $pid => $q) {
                    if (!Product::whereKey($pid)->exists()) continue;
                    $row = $this->lockRow($defaultId, (int) $pid);
                    $this->applyChange($row, $q, 'cancel_restore', $ctx);
                }
            }

            $order->forceFill(['stock_restored_at' => now()])->save();
            return true;
        });
    }

    /* =========================================================
     |  Ná»™i bá»™
     * ========================================================= */

    /** Cáº­p nháº­t products.stock = tá»•ng tá»“n cÃ¡c cá»­a hÃ ng */
    public function syncProductTotal(int $productId): int
    {
        $total = (int) StoreStock::where('product_id', $productId)->sum('quantity');
        Product::whereKey($productId)->update(['stock' => $total]);
        return $total;
    }

    /**
     * Sáº£n pháº©m táº¡o má»›i (chÆ°a cÃ³ dÃ²ng store_stocks) -> Ä‘Æ°a products.stock vÃ o kho máº·c Ä‘á»‹nh.
     */
    public function ensureProductInitialized(int $productId): void
    {
        if (StoreStock::where('product_id', $productId)->exists()) {
            return;
        }
        DB::transaction(function () use ($productId) {
            $product = Product::whereKey($productId)->lockForUpdate()->first();
            if (!$product) {
                throw new BusinessException("Sáº£n pháº©m #{$productId} khÃ´ng tá»“n táº¡i.");
            }
            if (StoreStock::where('product_id', $productId)->exists()) {
                return;
            }
            StoreStock::insertOrIgnore([
                'store_id' => $this->defaultStoreId(),
                'product_id' => $productId,
                'quantity' => max(0, (int) $product->stock),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });
    }

    /** Láº¥y (hoáº·c táº¡o) vÃ  KHÃ“A dÃ²ng tá»“n kho cá»§a má»™t cá»­a hÃ ng */
    protected function lockRow(int $storeId, int $productId): StoreStock
    {
        $this->ensureProductInitialized($productId);

        $row = StoreStock::where('store_id', $storeId)->where('product_id', $productId)->lockForUpdate()->first();
        if (!$row) {
            if (!Store::whereKey($storeId)->exists()) {
                throw new BusinessException("Cá»­a hÃ ng #{$storeId} khÃ´ng tá»“n táº¡i.");
            }
            StoreStock::insertOrIgnore([
                'store_id' => $storeId, 'product_id' => $productId, 'quantity' => 0,
                'created_at' => now(), 'updated_at' => now(),
            ]);
            $row = StoreStock::where('store_id', $storeId)->where('product_id', $productId)->lockForUpdate()->firstOrFail();
        }
        return $row;
    }

    /** Ãp biáº¿n Ä‘á»™ng lÃªn dÃ²ng Ä‘Ã£ khÃ³a, cháº·n tá»“n Ã¢m, ghi log, Ä‘á»“ng bá»™ tá»•ng */
    protected function applyChange(StoreStock $row, int $delta, string $type, array $ctx): InventoryLog
    {
        $newQty = (int) $row->quantity + $delta;
        if ($newQty < 0) {
            $product = Product::find($row->product_id);
            $store = Store::find($row->store_id);
            throw new InsufficientStockException(
                $row->product_id, $product->name ?? ('#' . $row->product_id),
                abs($delta), (int) $row->quantity, $store->name ?? null
            );
        }

        $row->quantity = $newQty;
        $row->save();
        $this->syncProductTotal($row->product_id);

        return InventoryLog::create([
            'product_id' => $row->product_id,
            'store_id' => $row->store_id,
            'order_id' => $ctx['order_id'] ?? null,
            'user_id' => $ctx['user_id'] ?? null,
            'type' => $type,
            'source' => $ctx['source'] ?? 'system',
            'reference_type' => $ctx['reference_type'] ?? null,
            'reference_id' => $ctx['reference_id'] ?? null,
            'quantity_changed' => $delta,
            'balance_after' => $newQty,
            'note' => $ctx['note'] ?? null,
        ]);
    }

    /** Thá»© tá»± xuáº¥t hÃ ng: kho máº·c Ä‘á»‹nh trÆ°á»›c, sau Ä‘Ã³ kho cÃ²n nhiá»u hÃ ng nháº¥t */
    protected function allocationOrder($rows, int $defaultId)
    {
        return $rows->sort(function ($a, $b) use ($defaultId) {
            return [(int) ((int) $b->store_id === $defaultId), (int) $b->quantity]
                <=> [(int) ((int) $a->store_id === $defaultId), (int) $a->quantity];
        })->values();
    }

    protected function assertPositive(int $qty): void
    {
        if ($qty <= 0) {
            throw new BusinessException('Sá»‘ lÆ°á»£ng pháº£i lá»›n hÆ¡n 0.');
        }
    }
}
