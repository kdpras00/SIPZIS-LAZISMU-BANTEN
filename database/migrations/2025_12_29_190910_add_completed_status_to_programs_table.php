<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify the status enum to include 'completed'
        DB::statement("ALTER TABLE programs MODIFY COLUMN status ENUM('active', 'inactive', 'completed') DEFAULT 'active'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        // First, update any 'completed' status to 'inactive'
        DB::statement("UPDATE programs SET status = 'inactive' WHERE status = 'completed'");
        
        // Then modify the enum back
        DB::statement("ALTER TABLE programs MODIFY COLUMN status ENUM('active', 'inactive') DEFAULT 'active'");
    }
};
