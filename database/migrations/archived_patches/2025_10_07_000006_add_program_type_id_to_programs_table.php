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
        Schema::table('programs', function (Blueprint $table) {
            // Add program_type_id column if it doesn't exist
            if (!Schema::hasColumn('programs', 'program_type_id')) {
                $table->foreignId('program_type_id')->nullable()->after('id');
            }

            // Add foreign key constraint for program_type_id
            if (Schema::hasColumn('programs', 'program_type_id')) {
                // Check if the foreign key constraint doesn't already exist
                $existingForeignKeys = collect(DB::select("
                    SELECT CONSTRAINT_NAME 
                    FROM information_schema.KEY_COLUMN_USAGE 
                    WHERE TABLE_SCHEMA = DATABASE() 
                    AND TABLE_NAME = 'programs' 
                    AND COLUMN_NAME = 'program_type_id'
                    AND REFERENCED_TABLE_NAME IS NOT NULL
                "))->pluck('CONSTRAINT_NAME')->toArray();

                // Only add the foreign key if it doesn't already exist
                if (!in_array('fk_programs_program_type', $existingForeignKeys)) {
                    $table->foreign('program_type_id', 'fk_programs_program_type')
                        ->references('id')->on('program_types')
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
        Schema::table('programs', function (Blueprint $table) {
            // Check if the foreign key constraint exists
            $existingForeignKeys = collect(DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'programs' 
                AND COLUMN_NAME = 'program_type_id'
                AND REFERENCED_TABLE_NAME IS NOT NULL
                AND CONSTRAINT_NAME = 'fk_programs_program_type'
            "))->pluck('CONSTRAINT_NAME')->toArray();

            // Drop foreign key constraint if it exists
            if (in_array('fk_programs_program_type', $existingForeignKeys)) {
                $table->dropForeign('fk_programs_program_type');
            }

            // Drop column if it exists
            if (Schema::hasColumn('programs', 'program_type_id')) {
                $table->dropColumn('program_type_id');
            }
        });
    }
};
