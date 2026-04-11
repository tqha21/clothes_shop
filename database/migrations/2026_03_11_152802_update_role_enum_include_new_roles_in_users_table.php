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
    public function up(): void
    {
        // Add new roles to ENUM
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'customer', 'employee', 'manager', 'staff') DEFAULT 'customer'");

        // Update existing employees to staff
        DB::table('users')->where('role', 'employee')->update(['role' => 'staff']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        // Revert to original ENUM (This will fail if there are manager/staff records not updated, so we might need point them to employee first)
        DB::table('users')->whereIn('role', ['staff', 'manager'])->update(['role' => 'employee']);
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'customer', 'employee') DEFAULT 'customer'");
    }
};
