<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE programs MODIFY COLUMN status ENUM('active', 'inactive', 'completed') DEFAULT 'active'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("UPDATE programs SET status = 'inactive' WHERE status = 'completed'");
            DB::statement("ALTER TABLE programs MODIFY COLUMN status ENUM('active', 'inactive') DEFAULT 'active'");
        }
    }
};
