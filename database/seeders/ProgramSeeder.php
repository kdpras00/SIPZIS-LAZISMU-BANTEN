<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Program;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programs = [
            [
                'name' => 'Program Pendidikan',
                'description' => 'Meningkatkan kualitas pendidikan melalui berbagai inisiatif',
                'category' => 'pendidikan',
                'status' => 'active'
            ],
            [
                'name' => 'Program Kesehatan',
                'description' => 'Memberikan akses layanan kesehatan yang terjangkau',
                'category' => 'kesehatan',
                'status' => 'active'
            ],
            [
                'name' => 'Program Ekonomi',
                'description' => 'Mendorong kemandirian ekonomi masyarakat',
                'category' => 'ekonomi',
                'status' => 'active'
            ],
            [
                'name' => 'Program Sosial & Dakwah',
                'description' => 'Mengembangkan kegiatan sosial dan dakwah',
                'category' => 'sosial-dakwah',
                'status' => 'active'
            ],
            [
                'name' => 'Program Kemanusiaan',
                'description' => 'Menyejahterakan umat manusia tanpa diskriminasi',
                'category' => 'kemanusiaan',
                'status' => 'active'
            ],
            [
                'name' => 'Program Lingkungan',
                'description' => 'Menjaga lingkungan untuk generasi mendatang',
                'category' => 'lingkungan',
                'status' => 'active'
            ],
            // Zakat programs
            [
                'name' => 'Zakat Mal',
                'description' => 'Zakat harta yang wajib dikeluarkan ketika harta telah mencapai nisab dan haul',
                'category' => 'zakat-mal',
                'status' => 'active'
            ],
            [
                'name' => 'Zakat Fitrah',
                'description' => 'Zakat yang wajib dikeluarkan menjelang Idul Fitri sebesar 3,5 liter beras per jiwa',
                'category' => 'zakat-fitrah',
                'status' => 'active'
            ],
            [
                'name' => 'Zakat Profesi',
                'description' => 'Zakat yang dikeluarkan dari penghasilan atau pendapatan seseorang',
                'category' => 'zakat-profesi',
                'status' => 'active'
            ],
            // Infaq programs
            [
                'name' => 'Infaq Masjid',
                'description' => 'Kontribusi untuk pembangunan, renovasi, dan pemeliharaan masjid serta fasilitas ibadah',
                'category' => 'infaq-masjid',
                'status' => 'active'
            ],
            [
                'name' => 'Infaq Pendidikan',
                'description' => 'Dukungan untuk program pendidikan, beasiswa, dan fasilitas belajar',
                'category' => 'infaq-pendidikan',
                'status' => 'active'
            ],
            [
                'name' => 'Infaq Kemanusiaan',
                'description' => 'Bantuan untuk korban bencana, musibah, dan kelompok masyarakat yang membutuhkan',
                'category' => 'infaq-kemanusiaan',
                'status' => 'active'
            ],
            // Shadaqah programs
            [
                'name' => 'Shadaqah Rutin',
                'description' => 'Donasi berkala setiap bulan untuk program-program berkelanjutan',
                'category' => 'shadaqah-rutin',
                'status' => 'active'
            ],
            [
                'name' => 'Shadaqah Jariyah',
                'description' => 'Donasi untuk proyek berkelanjutan yang manfaatnya terus mengalir',
                'category' => 'shadaqah-jariyah',
                'status' => 'active'
            ],
            [
                'name' => 'Fidyah',
                'description' => 'Tebusan untuk kewajiban ibadah yang tidak dilaksanakan',
                'category' => 'fidyah',
                'status' => 'active'
            ],
        ];

        foreach ($programs as $program) {
            if (!isset($program['slug'])) {
                $program['slug'] = \Illuminate\Support\Str::slug($program['name']);
            }
            Program::updateOrCreate(
                ['category' => $program['category']],
                $program
            );
        }
    }
}
