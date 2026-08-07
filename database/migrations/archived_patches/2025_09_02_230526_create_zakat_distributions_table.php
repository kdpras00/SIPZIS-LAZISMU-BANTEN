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
        Schema::create('zakat_distributions', function (Blueprint $table) {
            $table->id();
            $table->string('distribution_code')->unique(); // DIST-2024-001
            $table->foreignId('mustahik_id')->constrained('mustahik')->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->enum('distribution_type', ['cash', 'goods', 'voucher', 'service']);
            $table->text('goods_description')->nullable(); // If distribution_type is goods
            $table->date('distribution_date');
            $table->text('notes')->nullable();
            $table->string('program_name')->nullable(); // Program distribution (ramadan, etc)
            $table->foreignId('distributed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('location')->nullable(); // Distribution location
            $table->boolean('is_received')->default(false);
            $table->date('received_date')->nullable();
            $table->string('received_by_name')->nullable(); // If not received by mustahik directly
            $table->text('received_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zakat_distributions');
    }
};
