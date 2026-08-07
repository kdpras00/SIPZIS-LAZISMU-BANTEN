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
        Schema::create('mustahik', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nik', 20)->unique()->nullable();
            $table->enum('gender', ['male', 'female']);
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('phone', 20)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('category', [
                'fakir', 'miskin', 'amil', 'muallaf', 
                'riqab', 'gharim', 'fisabilillah', 'ibnu_sabil'
            ]); // 8 Asnaf (categories of zakat recipients)
            $table->text('category_description')->nullable();
            $table->enum('family_status', ['single', 'married', 'divorced', 'widow/widower'])->nullable();
            $table->integer('family_members')->default(1);
            $table->decimal('monthly_income', 15, 2)->nullable();
            $table->text('income_source')->nullable();
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->text('verification_notes')->nullable();
            $table->date('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mustahik');
    }
};