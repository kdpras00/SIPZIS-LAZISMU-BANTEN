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
        Schema::table('program_types', function (Blueprint $table) {
            // Check if columns don't exist before adding them
            if (!Schema::hasColumn('program_types', 'category')) {
                $table->string('category'); // zakat, infaq, shadaqah, program_pilar
            }

            if (!Schema::hasColumn('program_types', 'description')) {
                $table->text('description')->nullable();
            }

            if (!Schema::hasColumn('program_types', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }

            if (!Schema::hasColumn('program_types', 'created_at')) {
                $table->timestamps();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('program_types', function (Blueprint $table) {
            if (Schema::hasColumn('program_types', 'category')) {
                $table->dropColumn('category');
            }

            if (Schema::hasColumn('program_types', 'description')) {
                $table->dropColumn('description');
            }

            if (Schema::hasColumn('program_types', 'is_active')) {
                $table->dropColumn('is_active');
            }

            if (Schema::hasColumn('program_types', 'created_at')) {
                $table->dropColumn(['created_at', 'updated_at']);
            }
        });
    }
};
