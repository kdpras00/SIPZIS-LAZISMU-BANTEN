<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan penambahan index composite untuk optimasi query skala besar.
     */
    public function up(): void
    {
        Schema::table('zakat_payments', function (Blueprint $table) {
            $table->index(['status', 'payment_date'], 'idx_payments_status_date');
            $table->index(['program_id', 'status'], 'idx_payments_program_status');
            $table->index(['muzakki_id', 'status'], 'idx_payments_muzakki_status');
        });

        Schema::table('zakat_distributions', function (Blueprint $table) {
            $table->index(['distribution_type', 'distribution_date'], 'idx_dist_type_date');
            $table->index('mustahik_id', 'idx_dist_mustahik_id');
        });

        Schema::table('campaigns', function (Blueprint $table) {
            $table->index(['status', 'end_date'], 'idx_campaigns_status_end_date');
            $table->index(['program_category', 'status'], 'idx_campaigns_category_status');
        });
    }

    /**
     * Rollback penambahan index jika diperlukan.
     */
    public function down(): void
    {
        Schema::table('zakat_payments', function (Blueprint $table) {
            $table->dropIndex('idx_payments_status_date');
            $table->dropIndex('idx_payments_program_status');
            $table->dropIndex('idx_payments_muzakki_status');
        });

        Schema::table('zakat_distributions', function (Blueprint $table) {
            $table->dropIndex('idx_dist_type_date');
            $table->dropIndex('idx_dist_mustahik_id');
        });

        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropIndex('idx_campaigns_status_end_date');
            $table->dropIndex('idx_campaigns_category_status');
        });
    }
};
