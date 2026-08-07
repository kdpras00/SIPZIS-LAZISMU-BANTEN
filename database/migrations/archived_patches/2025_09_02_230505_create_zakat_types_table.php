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
        Schema::create('zakat_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Zakat Mal, Zakat Fitrah, Zakat Profesi
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('rate', 5, 4)->default(0.025); // Default 2.5%
            $table->decimal('nisab_amount', 15, 2)->nullable(); // Minimum threshold
            $table->string('nisab_unit')->nullable(); // gram, rupiah, etc
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zakat_types');
    }
};