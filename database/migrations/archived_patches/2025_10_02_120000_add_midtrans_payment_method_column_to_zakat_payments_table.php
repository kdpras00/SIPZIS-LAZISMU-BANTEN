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
        // Check if the column already exists
        if (!Schema::hasColumn('zakat_payments', 'midtrans_payment_method')) {
            Schema::table('zakat_payments', function (Blueprint $table) {
                $table->string('midtrans_payment_method')->nullable()->after('payment_method');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Check if the column exists before dropping
        if (Schema::hasColumn('zakat_payments', 'midtrans_payment_method')) {
            Schema::table('zakat_payments', function (Blueprint $table) {
                $table->dropColumn('midtrans_payment_method');
            });
        }
    }
};
