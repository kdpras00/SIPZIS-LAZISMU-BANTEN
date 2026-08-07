<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zakat_payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_code')->unique();
            $table->string('midtrans_order_id')->nullable()->index();
            $table->text('snap_token')->nullable();
            $table->foreignId('muzakki_id')->nullable()->constrained('muzakki')->onDelete('set null');
            $table->foreignId('program_id')->nullable()->constrained('programs')->onDelete('set null');
            $table->foreignId('campaign_id')->nullable()->constrained('campaigns')->onDelete('set null');
            $table->foreignId('received_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('program_category')->nullable();
            $table->date('payment_date')->nullable();
            $table->decimal('zakat_amount', 15, 2)->nullable();
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->string('payment_method')->default('cash');
            $table->string('midtrans_payment_method')->nullable();
            $table->string('payment_reference')->nullable();
            $table->string('receipt_number')->nullable()->unique();
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            $table->boolean('is_guest_payment')->default(false);
            $table->boolean('is_anonymous')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zakat_payments');
    }
};
