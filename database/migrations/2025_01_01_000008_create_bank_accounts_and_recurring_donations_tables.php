<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('muzakki_id')->constrained('muzakki')->onDelete('cascade');
            $table->string('bank_name');
            $table->string('account_number');
            $table->string('account_holder');
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        Schema::create('recurring_donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('muzakki_id')->constrained('muzakki')->onDelete('cascade');
            $table->foreignId('program_id')->nullable()->constrained('programs')->onDelete('set null');
            $table->decimal('amount', 15, 2);
            $table->enum('frequency', ['monthly', 'weekly'])->default('monthly');
            $table->date('start_date');
            $table->enum('status', ['active', 'paused', 'cancelled'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recurring_donations');
        Schema::dropIfExists('bank_accounts');
    }
};
