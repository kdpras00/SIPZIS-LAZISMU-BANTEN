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
        Schema::table('zakat_payments', function (Blueprint $table) {
            $table->foreignId('program_type_id')->nullable()->after('program_category');
            $table->foreign('program_type_id')->references('id')->on('program_types')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('zakat_payments', function (Blueprint $table) {
            $table->dropForeign(['program_type_id']);
            $table->dropColumn('program_type_id');
        });
    }
};
