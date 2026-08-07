<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProgramType;

class ProgramTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programTypes = [
            // Zakat types
            [
                'name' => 'Zakat Fitrah',
                'slug' => 'zakat-fitrah',
                'category' => 'zakat',
                'description' => 'Zakat yang wajib dikeluarkan setiap muslim pada bulan Ramadan',
                'is_active' => true,
            ],
            [
                'name' => 'Zakat Mal',
                'slug' => 'zakat-mal',
                'category' => 'zakat',
                'description' => 'Zakat yang dikeluarkan dari harta yang dimiliki',
                'is_active' => true,
            ],
            [
                'name' => 'Zakat Profesi',
                'slug' => 'zakat-profesi',
                'category' => 'zakat',
                'description' => 'Zakat yang dikeluarkan dari penghasilan profesi seperti gaji, honorarium, dan pendapatan lainnya',
                'is_active' => true,
            ],

            // Infaq types
            [
                'name' => 'Infaq Masjid',
                'slug' => 'infaq-masjid',
                'category' => 'infaq',
                'description' => 'Infaq untuk pembangunan dan pemeliharaan masjid',
                'is_active' => true,
            ],
            [
                'name' => 'Infaq Pendidikan',
                'slug' => 'infaq-pendidikan',
                'category' => 'infaq',
                'description' => 'Infaq untuk program pendidikan',
                'is_active' => true,
            ],
            [
                'name' => 'Infaq Kemanusiaan',
                'slug' => 'infaq-kemanusiaan',
                'category' => 'infaq',
                'description' => 'Infaq untuk program kemanusiaan',
                'is_active' => true,
            ],

            // Shadaqah types
            [
                'name' => 'Shadaqah Rutin',
                'slug' => 'shadaqah-rutin',
                'category' => 'shadaqah',
                'description' => 'Shadaqah yang diberikan secara rutin',
                'is_active' => true,
            ],
            [
                'name' => 'Shadaqah Jariyah',
                'slug' => 'shadaqah-jariyah',
                'category' => 'shadaqah',
                'description' => 'Shadaqah yang manfaatnya berkelanjutan',
                'is_active' => true,
            ],
            [
                'name' => 'Fidyah',
                'slug' => 'fidyah',
                'category' => 'shadaqah',
                'description' => 'Tebusan untuk mengganti kewajiban ibadah yang tidak dilaksanakan',
                'is_active' => true,
            ],

            // Program Pilar types
            [
                'name' => 'Pendidikan',
                'slug' => 'program-pendidikan',
                'category' => 'program_pilar',
                'description' => 'Program pilar pendidikan',
                'is_active' => true,
            ],
            [
                'name' => 'Kesehatan',
                'slug' => 'program-kesehatan',
                'category' => 'program_pilar',
                'description' => 'Program pilar kesehatan',
                'is_active' => true,
            ],
            [
                'name' => 'Ekonomi',
                'slug' => 'program-ekonomi',
                'category' => 'program_pilar',
                'description' => 'Program pilar ekonomi',
                'is_active' => true,
            ],
            [
                'name' => 'Sosial & Dakwah',
                'slug' => 'program-sosial-dakwah',
                'category' => 'program_pilar',
                'description' => 'Program pilar sosial dan dakwah',
                'is_active' => true,
            ],
            [
                'name' => 'Kemanusiaan',
                'slug' => 'program-kemanusiaan',
                'category' => 'program_pilar',
                'description' => 'Program pilar kemanusiaan',
                'is_active' => true,
            ],
            [
                'name' => 'Lingkungan',
                'slug' => 'program-lingkungan',
                'category' => 'program_pilar',
                'description' => 'Program pilar lingkungan',
                'is_active' => true,
            ],
        ];

        foreach ($programTypes as $type) {
            ProgramType::updateOrCreate(
                ['slug' => $type['slug']],
                $type
            );
        }
    }
}
