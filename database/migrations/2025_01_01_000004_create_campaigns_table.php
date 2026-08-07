<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->nullable()->constrained('programs')->onDelete('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('title');
            $table->text('description');
            $table->string('program_category')->nullable();
            $table->decimal('target_amount', 15, 2)->default(0);
            $table->decimal('collected_amount', 15, 2)->default(0);
            $table->string('photo')->nullable();
            $table->enum('status', ['draft', 'published', 'completed', 'cancelled'])->default('draft');
            $table->boolean('is_published')->default(false);
            $table->date('end_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
