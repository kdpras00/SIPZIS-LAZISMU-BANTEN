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
        Schema::table('muzakki', function (Blueprint $table) {
            // Change occupation column from enum to string to allow more values
            $table->string('occupation', 100)->nullable()->change();

            // Change location columns from string to allow longer values
            $table->string('province', 255)->nullable()->change();
            $table->string('city', 255)->nullable()->change();
            $table->string('district', 255)->nullable()->change();
            $table->string('village', 255)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('muzakki', function (Blueprint $table) {
            // Revert occupation column back to enum
            $table->enum('occupation', [
                'employee',
                'entrepreneur',
                'civil_servant',
                'teacher',
                'doctor',
                'farmer',
                'trader',
                'other'
            ])->nullable()->change();

            // Revert location columns back to original length
            $table->string('province', 255)->nullable()->change();
            $table->string('city', 255)->nullable()->change();
            $table->string('district', 255)->nullable()->change();
            $table->string('village', 255)->nullable()->change();
        });
    }
};
