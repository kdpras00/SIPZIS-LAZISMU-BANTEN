<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Program;

class ProgramTargetAmountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define target amounts for each program category
        $programTargets = [
            'pendidikan' => 100000000, // 100 million Rupiah
            'kesehatan' => 75000000,   // 75 million Rupiah
            'ekonomi' => 125000000,    // 125 million Rupiah
            'sosial-dakwah' => 50000000, // 50 million Rupiah
            'kemanusiaan' => 200000000,  // 200 million Rupiah
            'lingkungan' => 80000000,    // 80 million Rupiah
        ];

        // Update each program with its target amount
        foreach ($programTargets as $category => $targetAmount) {
            Program::where('category', $category)->update(['target_amount' => $targetAmount]);
        }
    }
}
