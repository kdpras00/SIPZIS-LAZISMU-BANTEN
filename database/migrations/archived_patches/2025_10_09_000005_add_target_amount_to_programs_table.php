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
            // Check if target_amount column exists before adding
            if (!Schema::hasColumn('programs', 'target_amount')) {
                $table->decimal('target_amount', 15, 2)->default(0)->after('category');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            if (Schema::hasColumn('programs', 'target_amount')) {
                $table->dropColumn('target_amount');
            }
        });
    }
};
