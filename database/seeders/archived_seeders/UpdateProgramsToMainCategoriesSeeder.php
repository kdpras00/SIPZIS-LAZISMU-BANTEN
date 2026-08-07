<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateProgramsToMainCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update zakat programs to use main category
        DB::table('programs')
            ->where('category', 'like', 'zakat-%')
            ->update(['category' => 'zakat']);

        // Update infaq programs to use main category
        DB::table('programs')
            ->where('category', 'like', 'infaq-%')
            ->update(['category' => 'infaq']);

        // Update shadaqah programs to use main category
        DB::table('programs')
            ->where('category', 'like', 'shadaqah-%')
            ->update(['category' => 'shadaqah']);

        // Note: Pilar programs (pendidikan, kesehatan, etc.) are already using main categories
        // so they don't need to be updated
    }
}
