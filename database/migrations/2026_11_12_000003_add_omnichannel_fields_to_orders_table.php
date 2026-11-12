<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Mở rộng orders / order_items cho bán hàng đa kênh.
 * Không xóa / ghi đè dữ liệu cũ: đơn cũ mặc định channel = online, đã trừ kho.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('code', 30)->nullable()->unique()->after('id');
            $table->enum('channel', ['online', 'pos'])->default('online')->after('code');
            $table->foreignId('store_id')->nullable()->after('channel')->constrained()->nullOnDelete();
            $table->foreignId('staff_id')->nullable()->after('store_id')->constrained('users')->nullOnDelete();
            $table->foreignId('shift_session_id')->nullable()->after('staff_id')->constrained('shift_sessions')->nullOnDelete();

            $table->decimal('subtotal', 15, 2)->nullable()->after('customer_address');
            $table->decimal('discount_amount', 15, 2)->default(0)->after('subtotal');
            $table->foreignId('coupon_id')->nullable()->after('discount_amount')->constrained()->nullOnDelete();
            $table->decimal('refunded_amount', 15, 2)->default(0)->after('total');

            $table->string('payment_status', 20)->default('unpaid')->after('payment_method'); // unpaid|pending|paid|partially_refunded|refunded|failed
            $table->boolean('stock_deducted')->default(false)->after('payment_status');
            $table->timestamp('stock_restored_at')->nullable()->after('stock_deducted');
            $table->timestamp('completed_at')->nullable()->after('stock_restored_at');
            $table->timestamp('cancelled_at')->nullable()->after('completed_at');
            $table->text('note')->nullable()->after('cancelled_at');

            $table->index(['channel', 'status']);
            $table->index('completed_at');
        });

        // Thêm trạng thái 'refunded' (hoàn toàn bộ)
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending','shipping','completed','cancelled','refunded') NOT NULL DEFAULT 'pending'");
        }

        // ===== Chuẩn hóa dữ liệu cũ =====
        DB::table('orders')->update(['channel' => 'online']);
        // Đơn cũ đã trừ kho qua CheckoutController (trừ đơn đã hủy — đã được hoàn kho trước đó)
        DB::table('orders')->where('status', '!=', 'cancelled')->update(['stock_deducted' => true]);
        DB::table('orders')->where('status', 'cancelled')->update([
            'stock_deducted' => true,
            'stock_restored_at' => DB::raw('updated_at'),
            'cancelled_at' => DB::raw('updated_at'),
        ]);
        DB::table('orders')->where('status', 'completed')->update([
            'completed_at' => DB::raw('updated_at'),
            'payment_status' => 'paid',
        ]);
        DB::statement("UPDATE orders SET code = CONCAT('ON', LPAD(id, 8, '0')) WHERE code IS NULL");
        DB::statement("UPDATE orders SET subtotal = total - shipping_fee WHERE subtotal IS NULL");

        Schema::table('order_items', function (Blueprint $table) {
            $table->string('product_name')->nullable()->after('product_id');     // Tên tại thời điểm bán
            $table->decimal('original_price', 15, 2)->nullable()->after('price'); // Giá niêm yết tại thời điểm bán
            $table->unsignedInteger('refunded_quantity')->default(0)->after('quantity');
        });

        DB::statement("UPDATE order_items oi JOIN products p ON p.id = oi.product_id SET oi.product_name = p.name WHERE oi.product_name IS NULL");
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['product_name', 'original_price', 'refunded_quantity']);
        });

        if (DB::getDriverName() === 'mysql') {
            DB::table('orders')->where('status', 'refunded')->update(['status' => 'cancelled']);
            DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending','shipping','completed','cancelled') NOT NULL DEFAULT 'pending'");
        }

        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['store_id']);
            $table->dropForeign(['staff_id']);
            $table->dropForeign(['shift_session_id']);
            $table->dropForeign(['coupon_id']);
            $table->dropIndex(['channel', 'status']);
            $table->dropIndex(['completed_at']);
            $table->dropUnique(['code']);
            $table->dropColumn([
                'code', 'channel', 'store_id', 'staff_id', 'shift_session_id',
                'subtotal', 'discount_amount', 'coupon_id', 'refunded_amount',
                'payment_status', 'stock_deducted', 'stock_restored_at',
                'completed_at', 'cancelled_at', 'note',
            ]);
        });
    }
};

