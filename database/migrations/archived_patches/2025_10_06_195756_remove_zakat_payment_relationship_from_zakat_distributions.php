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
            // Drop foreign key constraint if it exists
            $existingForeignKeys = collect(DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'zakat_distributions' 
                AND COLUMN_NAME = 'zakat_payment_id'
                AND REFERENCED_TABLE_NAME IS NOT NULL
            "))->pluck('CONSTRAINT_NAME')->toArray();

            if (in_array('fk_zakat_distributions_payment', $existingForeignKeys)) {
                $table->dropForeign('fk_zakat_distributions_payment');
            }

            // Drop column if it exists
            if (Schema::hasColumn('zakat_distributions', 'zakat_payment_id')) {
                $table->dropColumn('zakat_payment_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('zakat_distributions', function (Blueprint $table) {
            // Add column back if it doesn't exist
            if (!Schema::hasColumn('zakat_distributions', 'zakat_payment_id')) {
                $table->foreignId('zakat_payment_id')->nullable()->after('id');
            }

            // Add foreign key constraint back
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
        });
    }
};
