<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * shift_sessions = phiên làm việc THỰC TẾ (mở/đóng ca, đối soát tiền).
 * Khác với: shifts (định nghĩa ca) và shift_schedules (lịch phân công).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shift_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('code', 30)->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('store_id')->constrained()->cascadeOnDelete();
            $table->foreignId('shift_schedule_id')->nullable()->constrained('shift_schedules')->nullOnDelete();

            $table->timestamp('opened_at');
            $table->timestamp('closed_at')->nullable();

            $table->decimal('opening_cash', 15, 2)->default(0);   // Tiền đầu ca
            $table->decimal('cash_sales', 15, 2)->default(0);     // Thu tiền mặt bán hàng
            $table->decimal('cash_refunds', 15, 2)->default(0);   // Chi hoàn tiền mặt
            $table->decimal('cash_in', 15, 2)->default(0);        // Thu khác
            $table->decimal('cash_out', 15, 2)->default(0);       // Chi khác + nộp tiền
            $table->decimal('non_cash_sales', 15, 2)->default(0); // Thẻ / chuyển khoản / ví
            $table->decimal('expected_cash', 15, 2)->nullable();  // Tiền mặt dự kiến
            $table->decimal('counted_cash', 15, 2)->nullable();   // Tiền mặt kiểm đếm
            $table->decimal('difference', 15, 2)->nullable();     // Chênh lệch
            $table->unsignedInteger('orders_count')->default(0);

            $table->enum('status', ['open', 'closed'])->default('open');
            $table->text('open_note')->nullable();
            $table->text('close_note')->nullable();               // Lý do chênh lệch / ghi chú bàn giao
            $table->foreignId('handover_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('closed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['store_id', 'opened_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shift_sessions');
    }
};

