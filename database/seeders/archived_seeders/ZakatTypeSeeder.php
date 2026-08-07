<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ZakatType;

class ZakatTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $zakatTypes = [
            [
                'name' => 'Zakat Mal (Emas & Perak)',
                'slug' => 'zakat-mal-emas-perak',
                'description' => 'Zakat yang dikeluarkan dari kepemilikan emas dan perak dengan nisab 85 gram emas atau setara 595 gram perak',
                'rate' => 0.025, // 2.5%
                'nisab_amount' => 85000000, // Rp 85 juta (asumsi harga emas)
                'nisab_unit' => 'rupiah',
                'is_active' => true,
            ],
            [
                'name' => 'Zakat Mal (Uang & Tabungan)',
                'slug' => 'zakat-mal-uang-tabungan',
                'description' => 'Zakat yang dikeluarkan dari kepemilikan uang tunai, tabungan, deposito, dan sejenisnya',
                'rate' => 0.025, // 2.5%
                'nisab_amount' => 85000000, // Rp 85 juta
                'nisab_unit' => 'rupiah',
                'is_active' => true,
            ],
            [
                'name' => 'Zakat Profesi',
                'slug' => 'zakat-profesi',
                'description' => 'Zakat yang dikeluarkan dari penghasilan profesi seperti gaji, honorarium, dan pendapatan lainnya',
                'rate' => 0.025, // 2.5%
                'nisab_amount' => 7000000, // Rp 7 juta (asumsi nisab bulanan)
                'nisab_unit' => 'rupiah',
                'is_active' => true,
            ],
            [
                'name' => 'Zakat Fitrah',
                'slug' => 'zakat-fitrah',
                'description' => 'Zakat yang wajib dikeluarkan setiap muslim pada bulan Ramadan',
                'rate' => 1.0000, // Fixed amount per person
                'nisab_amount' => 35000, // Rp 35 ribu per orang (2024)
                'nisab_unit' => 'rupiah',
                'is_active' => true,
            ],
            [
                'name' => 'Zakat Perdagangan',
                'slug' => 'zakat-perdagangan',
                'description' => 'Zakat yang dikeluarkan dari modal dan keuntungan perdagangan',
                'rate' => 0.025, // 2.5%
                'nisab_amount' => 85000000, // Rp 85 juta
                'nisab_unit' => 'rupiah',
                'is_active' => true,
            ],
            [
                'name' => 'Zakat Pertanian',
                'slug' => 'zakat-pertanian',
                'description' => 'Zakat yang dikeluarkan dari hasil pertanian dan perkebunan',
                'rate' => 0.05, // 5% jika dengan pengairan, 10% jika tadah hujan
                'nisab_amount' => 653, // 653 kg gabah kering
                'nisab_unit' => 'kg',
                'is_active' => true,
            ],
        ];

        foreach ($zakatTypes as $type) {
            ZakatType::create($type);
        }
    }
}
