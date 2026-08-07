<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Region;

class AdditionalCountriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = ['Malaysia', 'Singapore', 'Thailand', 'Philippines', 'Vietnam', 'Myanmar', 'Cambodia', 'Laos', 'Brunei', 'Timor-Leste'];

        foreach ($countries as $countryName) {
            // Check if country already exists
            $existingCountry = Region::where('name', $countryName)->where('type', 'country')->first();

            if (!$existingCountry) {
                Region::create([
                    'name' => $countryName,
                    'type' => 'country',
                    'parent_id' => null
                ]);
            }
        }
    }
}
