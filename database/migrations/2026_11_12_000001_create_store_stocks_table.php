<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Tồn kho theo cửa hàng.
 * - Hệ thống chưa có bảng biến thể độc lập (size/màu lưu dạng JSON trên products),
 *   nên khóa duy nhất là (store_id, product_id). Size/màu vẫn được lưu trên order_items.
 * - products.stock được giữ lại và luôn = tổng store_stocks.quantity (StockService đồng bộ).
 * - Dữ liệu cũ: toàn bộ products.stock hiện tại được chuyển vào "kho mặc định".
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            if (!Schema::hasColumn('stores', 'code')) {
                $table->string('code', 20)->nullable()->after('id');
            }
            if (!Schema::hasColumn('stores', 'is_default_warehouse')) {
                // Kho mặc định: nhận tồn kho cũ, ưu tiên xuất cho đơn online
                $table->boolean('is_default_warehouse')->default(false)->after('status');
            }
        });

        Schema::create('store_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('quantity')->default(0);
            $table->unsignedInteger('low_stock_threshold')->default(5);
            $table->timestamp('last_counted_at')->nullable();
            $table->timestamps();

            $table->unique(['store_id', 'product_id']);
            $table->index('product_id');
        });

        // ===== Chuyển dữ liệu tồn kho cũ =====
        $defaultStoreId = DB::table('stores')->where('status', 'active')->orderBy('id')->value('id')
            ?? DB::table('stores')->orderBy('id')->value('id');

        if (!$defaultStoreId) {
            $defaultStoreId = DB::table('stores')->insertGetId([
                'code' => 'KHO-TONG',
                'name' => 'Kho Tổng HTD Store',
                'address' => 'Kho trung tâm',
                'city' => 'Hà Nội',
                'district' => 'Trung tâm',
                'phone' => '0000000000',
                'latitude' => 0,
                'longitude' => 0,
                'status' => 'active',
                'is_default_warehouse' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            DB::table('stores')->where('id', $defaultStoreId)->update(['is_default_warehouse' => true]);
        }

        // Sinh mã cửa hàng cho các store chưa có mã
        foreach (DB::table('stores')->whereNull('code')->pluck('id') as $id) {
            DB::table('stores')->where('id', $id)->update(['code' => 'CH' . str_pad($id, 3, '0', STR_PAD_LEFT)]);
        }

        DB::table('products')->select('id', 'stock')->orderBy('id')->chunk(500, function ($products) use ($defaultStoreId) {
            $rows = [];
            foreach ($products as $p) {
                $rows[] = [
                    'store_id' => $defaultStoreId,
                    'product_id' => $p->id,
                    'quantity' => max(0, (int) $p->stock),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            if ($rows) {
                DB::table('store_stocks')->insertOrIgnore($rows);
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_stocks');
        Schema::table('stores', function (Blueprint $table) {
            if (Schema::hasColumn('stores', 'is_default_warehouse')) {
                $table->dropColumn('is_default_warehouse');
            }
            if (Schema::hasColumn('stores', 'code')) {
                $table->dropColumn('code');
            }
        });
    }
};

