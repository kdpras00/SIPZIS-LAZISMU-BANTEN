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
            // Check if columns exist before dropping them
            if (Schema::hasColumn('zakat_payments', 'wealth_amount')) {
                $table->dropColumn('wealth_amount');
            }

            if (Schema::hasColumn('zakat_payments', 'hijri_year')) {
                $table->dropColumn('hijri_year');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('zakat_payments', function (Blueprint $table) {
            $table->decimal('wealth_amount', 15, 2)->nullable()->after('zakat_type_id');
            $table->integer('hijri_year')->nullable()->after('payment_date');
        });
    }
};
