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
        $admin = User::create([
            'name' => 'Administrator Lazismu Banten',
            'email' => 'admin@sipzis.com',
            'password' => Hash::make('password'),
            'is_active' => true,
            'phone' => '081234567890',
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');


        // Create Sample Muzakki Users
        $muzakki1 = User::create([
            'name' => 'Ahmad Muzakki',
            'email' => 'ahmad@sipzis.com',
            'password' => Hash::make('password'),
            'is_active' => true,
            'phone' => '081234567893',
            'email_verified_at' => now(),
        ]);
        $muzakki1->assignRole('muzakki');

        $muzakki2 = User::create([
            'name' => 'Fatimah Zakat',
            'email' => 'fatimah@sipzis.com',
            'password' => Hash::make('password'),
            'is_active' => true,
            'phone' => '081234567894',
            'email_verified_at' => now(),
        ]);
        $muzakki2->assignRole('muzakki');

        $muzakki3 = User::create([
            'name' => 'Muhammad Dermawan',
            'email' => 'muhammad@sipzis.com',
            'password' => Hash::make('password'),
            'is_active' => true,
            'phone' => '081234567895',
            'email_verified_at' => now(),
        ]);
        $muzakki3->assignRole('muzakki');
    }
}
