<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_transfers', function (Blueprint $table) {
            $table->id();
            $table->string('code', 30)->unique();
            $table->foreignId('from_store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignId('to_store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('quantity');
            $table->enum('status', ['completed', 'cancelled'])->default('completed');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->text('note')->nullable();
            $table->timestamps();
        });

        // Chấm công thực tế trên lịch phân công (không dùng status 'completed' làm bằng chứng chấm công)
        Schema::table('shift_schedules', function (Blueprint $table) {
            $table->timestamp('check_in_at')->nullable()->after('status');
            $table->timestamp('check_out_at')->nullable()->after('check_in_at');
        });
    }

    public function down(): void
    {
        Schema::table('shift_schedules', function (Blueprint $table) {
            $table->dropColumn(['check_in_at', 'check_out_at']);
        });
        Schema::dropIfExists('stock_transfers');
    }
};

