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
        if (Schema::hasTable('shifts') && !Schema::hasColumn('shifts', 'min_staff')) {
            Schema::table('shifts', function (Blueprint $table) {
                $table->unsignedInteger('min_staff')->default(1)->after('color');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('shifts') && Schema::hasColumn('shifts', 'min_staff')) {
            Schema::table('shifts', function (Blueprint $table) {
                $table->dropColumn('min_staff');
            });
        }
    }
};

