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
        // First, identify and remove duplicate muzakki records, keeping the one with the highest ID
        DB::statement("
            DELETE m1 FROM muzakki m1
            INNER JOIN muzakki m2
            WHERE m1.id < m2.id AND m1.email = m2.email
        ");

        // Ensure the email column has a unique index
        Schema::table('muzakki', function (Blueprint $table) {
            // Check if the unique index already exists
            $indexExists = DB::select("
                SELECT COUNT(*) as count
                FROM information_schema.statistics
                WHERE table_schema = DATABASE()
                AND table_name = 'muzakki'
                AND column_name = 'email'
                AND non_unique = 0
            ")[0]->count > 0;

            // Add unique index if it doesn't exist
            if (!$indexExists) {
                $table->unique('email');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('muzakki', function (Blueprint $table) {
            // Check if the unique index exists before trying to drop it
            $indexExists = DB::select("
                SELECT COUNT(*) as count
                FROM information_schema.statistics
                WHERE table_schema = DATABASE()
                AND table_name = 'muzakki'
                AND column_name = 'email'
                AND non_unique = 0
            ")[0]->count > 0;

            // Drop unique index if it exists
            if ($indexExists) {
                $table->dropUnique(['email']);
            }
        });
    }
};
