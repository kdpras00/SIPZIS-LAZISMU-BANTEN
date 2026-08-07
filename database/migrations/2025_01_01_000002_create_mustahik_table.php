<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mustahik', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nik', 16)->nullable()->unique();
            $table->enum('gender', ['male', 'female'])->default('male');
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('phone')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('category', ['fakir', 'miskin', 'amil', 'muallaf', 'riqab', 'gharim', 'fisabilillah', 'ibnu_sabil']);
            $table->text('category_description')->nullable();
            $table->enum('family_status', ['single', 'married', 'divorced', 'widow/widower'])->nullable();
            $table->integer('family_members')->default(1);
            $table->decimal('monthly_income', 15, 2)->default(0);
            $table->string('income_source')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mustahik');
    }
};
