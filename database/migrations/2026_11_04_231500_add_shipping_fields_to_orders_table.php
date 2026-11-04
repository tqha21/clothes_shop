<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shipping_method')->default('standard')->after('payment_method');
            $table->string('shipping_carrier')->default('SPX Express (Shopee Xpress)')->after('shipping_method');
            $table->decimal('shipping_fee', 10, 2)->default(0)->after('shipping_carrier');
            $table->string('tracking_code')->nullable()->after('shipping_fee');
            $table->string('estimated_delivery')->nullable()->after('tracking_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_method',
                'shipping_carrier',
                'shipping_fee',
                'tracking_code',
                'estimated_delivery'
            ]);
        });
    }
};

