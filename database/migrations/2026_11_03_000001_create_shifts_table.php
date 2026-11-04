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
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);          // Tên ca: "Ca Sáng", "Ca Chiều", "Ca Tối"
            $table->time('start_time');            // Giờ bắt đầu
            $table->time('end_time');              // Giờ kết thúc
            $table->string('color', 20)->default('#3B82F6'); // Màu hex cho lịch
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
