<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('muzakki', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable();
            $table->boolean('phone_verified')->default(false);
            $table->string('nik', 16)->nullable();
            $table->enum('gender', ['male', 'female'])->default('male');
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('district')->nullable();
            $table->string('village')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->default('Indonesia');
            $table->string('campaign_url')->nullable();
            $table->string('profile_photo')->nullable();
            $table->string('ktp_photo')->nullable();
            $table->text('bio')->nullable();
            $table->string('occupation')->nullable();
            $table->decimal('monthly_income', 15, 2)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('muzakki');
    }
};
