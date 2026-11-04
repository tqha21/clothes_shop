<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shift_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('shift_id')->constrained('shifts')->onDelete('cascade');
            $table->date('work_date');             // Ngày làm việc
            $table->text('note')->nullable();      // Ghi chú
            $table->enum('status', ['scheduled', 'confirmed', 'absent', 'completed'])
                  ->default('scheduled');          // Trạng thái
            $table->timestamps();

            // Unique constraint: không trùng nhân viên + ca + ngày
            $table->unique(['user_id', 'shift_id', 'work_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shift_schedules');
    }
};
