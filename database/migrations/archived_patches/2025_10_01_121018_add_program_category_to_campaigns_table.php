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
        // Check if the column already exists before adding it
        if (!Schema::hasColumn('campaigns', 'program_category')) {
            Schema::table('campaigns', function (Blueprint $table) {
                $table->string('program_category')->after('description');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Check if the column exists before dropping it
        if (Schema::hasColumn('campaigns', 'program_category')) {
            Schema::table('campaigns', function (Blueprint $table) {
                $table->dropColumn('program_category');
            });
        }
    }
};