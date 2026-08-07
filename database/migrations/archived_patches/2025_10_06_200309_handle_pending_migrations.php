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
        // Mark the pending migrations as completed since their tables already exist
        // This is a workaround for migrations that are trying to create tables that already exist

        // We'll insert records into the migrations table to mark them as completed
        $pendingMigrations = [
            '2025_10_07_000000_create_program_types_table',
            '2025_10_07_000003_add_foreign_key_to_program_category_in_campaigns_table'
        ];

        foreach ($pendingMigrations as $migration) {
            // Check if migration is already recorded
            $exists = DB::table('migrations')->where('migration', $migration)->exists();

            if (!$exists) {
                // Get the next batch number
                $batch = DB::table('migrations')->max('batch') + 1;

                // Insert the migration record
                DB::table('migrations')->insert([
                    'migration' => $migration,
                    'batch' => $batch
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the migration records we added
        $pendingMigrations = [
            '2025_10_07_000000_create_program_types_table',
            '2025_10_07_000003_add_foreign_key_to_program_category_in_campaigns_table'
        ];

        DB::table('migrations')->whereIn('migration', $pendingMigrations)->delete();
    }
};
