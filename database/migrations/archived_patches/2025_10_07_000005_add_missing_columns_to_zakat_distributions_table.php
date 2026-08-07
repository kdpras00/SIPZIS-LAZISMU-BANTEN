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
        Schema::table('zakat_distributions', function (Blueprint $table) {
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('zakat_distributions', 'zakat_payment_id')) {
                $table->foreignId('zakat_payment_id')->nullable()->after('id');
            }

            // Add foreign key constraint for zakat_payment_id
            if (Schema::hasColumn('zakat_distributions', 'zakat_payment_id')) {
                // Check if the foreign key constraint doesn't already exist
                $existingForeignKeys = collect(DB::select("
                    SELECT CONSTRAINT_NAME 
                    FROM information_schema.KEY_COLUMN_USAGE 
                    WHERE TABLE_SCHEMA = DATABASE() 
                    AND TABLE_NAME = 'zakat_distributions' 
                    AND COLUMN_NAME = 'zakat_payment_id'
                    AND REFERENCED_TABLE_NAME IS NOT NULL
                "))->pluck('CONSTRAINT_NAME')->toArray();

                // Only add the foreign key if it doesn't already exist
                if (!in_array('fk_zakat_distributions_payment', $existingForeignKeys)) {
                    $table->foreign('zakat_payment_id', 'fk_zakat_distributions_payment')
                        ->references('id')->on('zakat_payments')
                        ->onDelete('set null')
                        ->onUpdate('restrict');
                }
            }

            // Add foreign key constraint for program_name if it references programs.category
            if (Schema::hasColumn('zakat_distributions', 'program_name')) {
                // Check if the foreign key constraint doesn't already exist
                $existingForeignKeys = collect(DB::select("
                    SELECT CONSTRAINT_NAME 
                    FROM information_schema.KEY_COLUMN_USAGE 
                    WHERE TABLE_SCHEMA = DATABASE() 
                    AND TABLE_NAME = 'zakat_distributions' 
                    AND COLUMN_NAME = 'program_name'
                    AND REFERENCED_TABLE_NAME IS NOT NULL
                "))->pluck('CONSTRAINT_NAME')->toArray();

                // Only add the foreign key if it doesn't already exist
                if (!in_array('fk_distribution_program', $existingForeignKeys)) {
                    $table->foreign('program_name', 'fk_distribution_program')
                        ->references('category')->on('programs')
                        ->onDelete('set null')
                        ->onUpdate('restrict');
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('zakat_distributions', function (Blueprint $table) {
            // Drop foreign key constraints if they exist
            $existingForeignKeys = collect(DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'zakat_distributions' 
                AND REFERENCED_TABLE_NAME IS NOT NULL
                AND (CONSTRAINT_NAME = 'fk_zakat_distributions_payment' OR CONSTRAINT_NAME = 'fk_distribution_program')
            "))->pluck('CONSTRAINT_NAME')->toArray();

            if (in_array('fk_zakat_distributions_payment', $existingForeignKeys)) {
                $table->dropForeign('fk_zakat_distributions_payment');
            }

            if (in_array('fk_distribution_program', $existingForeignKeys)) {
                $table->dropForeign('fk_distribution_program');
            }

            // Drop column if it exists
            if (Schema::hasColumn('zakat_distributions', 'zakat_payment_id')) {
                $table->dropColumn('zakat_payment_id');
            }
        });
    }
};
