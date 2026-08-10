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
        if (Schema::hasTable('zakat_payments') && !Schema::hasTable('payments')) {
            Schema::rename('zakat_payments', 'payments');
        }
        if (Schema::hasTable('zakat_distributions') && !Schema::hasTable('distributions')) {
            Schema::rename('zakat_distributions', 'distributions');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('payments') && !Schema::hasTable('zakat_payments')) {
            Schema::rename('payments', 'zakat_payments');
        }
        if (Schema::hasTable('distributions') && !Schema::hasTable('zakat_distributions')) {
            Schema::rename('distributions', 'zakat_distributions');
        }
    }
};
