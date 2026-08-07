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
            // First drop the foreign key constraint if it exists
            $existingForeignKeys = collect(DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'zakat_payments' 
                AND COLUMN_NAME = 'zakat_type_id'
                AND REFERENCED_TABLE_NAME IS NOT NULL
            "))->pluck('CONSTRAINT_NAME')->toArray();

            if (in_array('zakat_payments_zakat_type_id_foreign', $existingForeignKeys)) {
                $table->dropForeign('zakat_payments_zakat_type_id_foreign');
            }

            // Then drop the column if it exists
            if (Schema::hasColumn('zakat_payments', 'zakat_type_id')) {
                $table->dropColumn('zakat_type_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('zakat_payments', function (Blueprint $table) {
            // Add column back if it doesn't exist
            if (!Schema::hasColumn('zakat_payments', 'zakat_type_id')) {
                $table->foreignId('zakat_type_id')->nullable()->after('muzakki_id');
            }

            // Add foreign key constraint back
            if (Schema::hasColumn('zakat_payments', 'zakat_type_id')) {
                // Check if the foreign key constraint doesn't already exist
                $existingForeignKeys = collect(DB::select("
                    SELECT CONSTRAINT_NAME 
                    FROM information_schema.KEY_COLUMN_USAGE 
                    WHERE TABLE_SCHEMA = DATABASE() 
                    AND TABLE_NAME = 'zakat_payments' 
                    AND COLUMN_NAME = 'zakat_type_id'
                    AND REFERENCED_TABLE_NAME IS NOT NULL
                "))->pluck('CONSTRAINT_NAME')->toArray();

                // Only add the foreign key if it doesn't already exist
                if (!in_array('zakat_payments_zakat_type_id_foreign', $existingForeignKeys)) {
                    $table->foreign('zakat_type_id')
                        ->references('id')->on('zakat_types')
                        ->onDelete('set null')
                        ->onUpdate('restrict');
                }
            }
        });
    }
};
