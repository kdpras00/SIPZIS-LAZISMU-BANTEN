<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Administrator Lazismu Banten',
            'email' => 'admin@sipzis.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
            'phone' => '081234567890',
            'email_verified_at' => now(),
        ]);


        // Create Sample Muzakki Users
        User::create([
            'name' => 'Ahmad Muzakki',
            'email' => 'ahmad@sipzis.com',
            'password' => Hash::make('password'),
            'role' => 'muzakki',
            'is_active' => true,
            'phone' => '081234567893',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Fatimah Zakat',
            'email' => 'fatimah@sipzis.com',
            'password' => Hash::make('password'),
            'role' => 'muzakki',
            'is_active' => true,
            'phone' => '081234567894',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Muhammad Dermawan',
            'email' => 'muhammad@sipzis.com',
            'password' => Hash::make('password'),
            'role' => 'muzakki',
            'is_active' => true,
            'phone' => '081234567895',
            'email_verified_at' => now(),
        ]);
    }
}
