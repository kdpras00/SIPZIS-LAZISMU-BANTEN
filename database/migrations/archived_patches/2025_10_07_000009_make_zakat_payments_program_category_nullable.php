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
        // First, clean up any invalid references by setting them to a default value
        // We'll use a temporary value that we can later set to NULL
        DB::statement("
            UPDATE zakat_payments 
            SET program_category = 'temp_cleanup' 
            WHERE program_category NOT IN (SELECT category FROM programs WHERE category IS NOT NULL)
        ");

        // Make the column nullable
        Schema::table('zakat_payments', function (Blueprint $table) {
            $table->string('program_category')->nullable()->change();
        });

        // Now set the invalid references to NULL
        DB::statement("
            UPDATE zakat_payments 
            SET program_category = NULL 
            WHERE program_category = 'temp_cleanup'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Set NULL values to a default value before making the column NOT NULL
        DB::statement("
            UPDATE zakat_payments 
            SET program_category = 'unknown' 
            WHERE program_category IS NULL
        ");

        // Make the column NOT NULL again
        Schema::table('zakat_payments', function (Blueprint $table) {
            $table->string('program_category')->nullable(false)->change();
        });
    }
};
