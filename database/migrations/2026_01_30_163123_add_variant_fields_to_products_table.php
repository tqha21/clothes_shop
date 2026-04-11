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
        Schema::table('products', function (Blueprint $table) {
            $table->json('sizes')->nullable()->after('stock'); // ['S', 'M', 'L', 'XL', 'XXL']
            $table->json('colors')->nullable()->after('sizes'); // ['Đen', 'Trắng', 'Xanh']
            $table->integer('view_count')->default(0)->after('stock');
            $table->integer('sold_count')->default(0)->after('view_count');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['sizes', 'colors', 'view_count', 'sold_count']);
        });
    }
};
