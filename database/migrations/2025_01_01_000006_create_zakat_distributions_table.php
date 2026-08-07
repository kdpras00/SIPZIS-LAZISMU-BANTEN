<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zakat_distributions', function (Blueprint $table) {
            $table->id();
            $table->string('distribution_code')->unique();
            $table->foreignId('mustahik_id')->constrained('mustahik')->onDelete('cascade');
            $table->foreignId('program_id')->nullable()->constrained('programs')->onDelete('set null');
            $table->string('program_name')->nullable();
            $table->foreignId('distributed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->date('distribution_date');
            $table->decimal('amount', 15, 2);
            $table->enum('distribution_type', ['cash', 'goods', 'voucher', 'service'])->default('cash');
            $table->text('goods_description')->nullable();
            $table->enum('status', ['planned', 'distributed', 'cancelled'])->default('distributed');
            $table->text('notes')->nullable();
            $table->string('location')->nullable();
            $table->boolean('is_received')->default(false);
            $table->date('received_date')->nullable();
            $table->string('received_by_name')->nullable();
            $table->text('received_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zakat_distributions');
    }
};
