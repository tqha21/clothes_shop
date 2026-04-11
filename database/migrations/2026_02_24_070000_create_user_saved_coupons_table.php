<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_saved_coupons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('coupon_id')->constrained()->onDelete('cascade');
            $table->timestamp('saved_at')->useCurrent();
            $table->boolean('is_used')->default(false);
            $table->timestamps();

            // Mỗi user chỉ lưu 1 lần mỗi coupon
            $table->unique(['user_id', 'coupon_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_saved_coupons');
    }
};
