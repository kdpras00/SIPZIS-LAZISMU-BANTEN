<?php

namespace App\Services;

use App\Models\User;
use App\Models\Muzakki;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DonationService
{
    
    public function processGuestDonation(array $data): Payment
    {
        return DB::transaction(function () use ($data) {
            $muzakki = $this->resolveMuzakki($data);

            $paymentCode = Payment::generatePaymentCode();
            
            $payment = Payment::create([
                'payment_code' => $paymentCode,
                'muzakki_id' => $muzakki->id,
                'program_id' => $data['program_id'] ?? null,
                'program_category' => $data['program_category'] ?? 'umum',
                'zakat_amount' => $data['amount'],
                'paid_amount' => $data['amount'],
                'payment_method' => $data['payment_method'],
                'midtrans_payment_method' => null, 
                'payment_date' => now(),
                'status' => 'pending',
                'notes' => $data['notes'] ?? null,
                'is_guest_payment' => true,
                'is_anonymous' => $data['is_anonymous'] ?? false,
            ]);

            return $payment;
        });
    }

    
    protected function resolveMuzakki(array $data): Muzakki
    {
        $muzakki = null;
        
        if (!empty($data['email'])) {
            $muzakki = Muzakki::where('email', $data['email'])->first();
        }

        if (!$muzakki && !empty($data['phone'])) {
            $muzakki = Muzakki::where('phone', $data['phone'])->first();
        }

        if (!$muzakki) {
            $user = null;
            if (!empty($data['email'])) {
                $user = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make(Str::random(16)),
                    'role' => 'muzakki',
                    'is_active' => true,
                ]);
            }

            $muzakki = Muzakki::create([
                'name' => $data['name'],
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'],
                'gender' => 'male', 
                'user_id' => $user?->id,
                'is_active' => true,
            ]);
        }

        return $muzakki;
    }
}
