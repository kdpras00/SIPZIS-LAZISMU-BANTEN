<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update all programs to use only main categories
        DB::table('programs')->where('category', 'like', 'zakat-%')->update(['category' => 'zakat']);
        DB::table('programs')->where('category', 'like', 'infaq-%')->update(['category' => 'infaq']);
        DB::table('programs')->where('category', 'like', 'shadaqah-%')->update(['category' => 'shadaqah']);

        // For pilar programs, keep the specific category names as they are the main categories
        // (pendidikan, kesehatan, ekonomi, sosial-dakwah, kemanusiaan, lingkungan)
        // These don't need to be changed as they're already main categories
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We cannot reliably reverse this migration as we've lost the original sub-category information
        // In a real scenario, you might want to back up the data before running this migration
    }
};
