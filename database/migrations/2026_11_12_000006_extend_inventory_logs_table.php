<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Mở rộng inventory_logs để truy vết đầy đủ:
 * cửa hàng, đơn hàng, nguồn giao dịch, tồn sau biến động.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Mở rộng loại biến động (enum -> varchar) để hỗ trợ: return, cancel_restore, transfer_in, transfer_out, stocktake
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE inventory_logs MODIFY COLUMN type VARCHAR(30) NOT NULL");
        }

        Schema::table('inventory_logs', function (Blueprint $table) {
            $table->foreignId('store_id')->nullable()->after('product_id')->constrained()->nullOnDelete();
            $table->foreignId('order_id')->nullable()->after('store_id')->constrained()->nullOnDelete();
            $table->string('source', 20)->default('admin')->after('type'); // online|pos|admin|system|transfer
            $table->string('reference_type', 50)->nullable()->after('source');
            $table->unsignedBigInteger('reference_id')->nullable()->after('reference_type');
            $table->integer('balance_after')->nullable()->after('quantity_changed');

            $table->index(['store_id', 'product_id']);
            $table->index(['order_id', 'type']);
        });

        // Gắn kho mặc định cho log cũ
        $defaultStoreId = DB::table('stores')->where('is_default_warehouse', true)->value('id');
        if ($defaultStoreId) {
            DB::table('inventory_logs')->whereNull('store_id')->update(['store_id' => $defaultStoreId]);
        }
        DB::table('inventory_logs')->where('type', 'sale')->update(['source' => 'online']);
    }

    public function down(): void
    {
        Schema::table('inventory_logs', function (Blueprint $table) {
            $table->dropForeign(['store_id']);
            $table->dropForeign(['order_id']);
            $table->dropIndex(['store_id', 'product_id']);
            $table->dropIndex(['order_id', 'type']);
            $table->dropColumn(['store_id', 'order_id', 'source', 'reference_type', 'reference_id', 'balance_after']);
        });
    }
};

