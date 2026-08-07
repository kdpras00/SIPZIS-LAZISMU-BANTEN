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
        Schema::table('campaigns', function (Blueprint $table) {
            // Check if the old constraint exists
            $existingForeignKeys = collect(DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'campaigns' 
                AND COLUMN_NAME = 'program_id'
                AND REFERENCED_TABLE_NAME IS NOT NULL
                AND CONSTRAINT_NAME = 'campaigns_program_id_foreign'
            "))->pluck('CONSTRAINT_NAME')->toArray();

            // If the old constraint exists, drop it and create the new one with the proper name
            if (in_array('campaigns_program_id_foreign', $existingForeignKeys)) {
                $table->dropForeign('campaigns_program_id_foreign');
                $table->foreign('program_id', 'fk_campaign_created_by')
                    ->references('id')->on('programs')
                    ->onDelete('set null')
                    ->onUpdate('restrict');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            // Check if the new constraint exists
            $existingForeignKeys = collect(DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'campaigns' 
                AND COLUMN_NAME = 'program_id'
                AND REFERENCED_TABLE_NAME IS NOT NULL
                AND CONSTRAINT_NAME = 'fk_campaign_created_by'
            "))->pluck('CONSTRAINT_NAME')->toArray();

            // If the new constraint exists, drop it and recreate the old one
            if (in_array('fk_campaign_created_by', $existingForeignKeys)) {
                $table->dropForeign('fk_campaign_created_by');
                $table->foreign('program_id')->references('id')->on('programs')->onDelete('set null');
            }
        });
    }
};
