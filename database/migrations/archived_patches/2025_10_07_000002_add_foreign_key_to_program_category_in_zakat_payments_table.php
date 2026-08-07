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
        Schema::table('zakat_payments', function (Blueprint $table) {
            // First check if the foreign key constraint doesn't already exist
            $existingForeignKeys = collect(DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'zakat_payments' 
                AND COLUMN_NAME = 'program_category'
                AND REFERENCED_TABLE_NAME IS NOT NULL
            "))->pluck('CONSTRAINT_NAME')->toArray();

            // Only add the foreign key if it doesn't already exist
            if (!in_array('fk_payment_program', $existingForeignKeys)) {
                $table->foreign('program_category', 'fk_payment_program')
                    ->references('category')->on('programs')
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
        Schema::table('zakat_payments', function (Blueprint $table) {
            $table->dropForeign('fk_payment_program');
        });
    }
};
