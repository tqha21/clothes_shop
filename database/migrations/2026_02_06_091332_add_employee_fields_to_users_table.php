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
        Schema::table('users', function (Blueprint $table) {
            $table->string('employee_code', 20)->nullable()->unique()->after('phone');
            $table->string('position', 100)->nullable()->after('employee_code');
            $table->string('department', 100)->nullable()->after('position');
            $table->date('hire_date')->nullable()->after('department');
            $table->decimal('salary', 15, 2)->nullable()->after('hire_date');
            $table->enum('status', ['active', 'inactive'])->default('active')->after('salary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['employee_code', 'position', 'department', 'hire_date', 'salary', 'status']);
        });
    }
};
