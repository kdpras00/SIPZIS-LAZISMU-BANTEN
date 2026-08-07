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
        Schema::create('muzakki', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('nik', 20)->unique()->nullable(); // NIK/KTP
            $table->enum('gender', ['male', 'female']);
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->enum('occupation', [
                'employee', 'entrepreneur', 'civil_servant', 
                'teacher', 'doctor', 'farmer', 'trader', 'other'
            ])->nullable();
            $table->decimal('monthly_income', 15, 2)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('muzakki');
    }
};