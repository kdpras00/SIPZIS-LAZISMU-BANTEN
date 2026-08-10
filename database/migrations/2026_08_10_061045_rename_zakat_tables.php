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
        Schema::rename('zakat_payments', 'payments');
        Schema::rename('zakat_distributions', 'distributions');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('payments', 'zakat_payments');
        Schema::rename('distributions', 'zakat_distributions');
    }
};
