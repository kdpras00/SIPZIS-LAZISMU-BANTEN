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
        // Check if the foreign key exists before trying to drop it
        // This migration might have already been run or might not exist
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
