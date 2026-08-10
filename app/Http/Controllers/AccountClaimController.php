<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AccountClaimController extends Controller
{
    
    public function claim(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'payment_code' => 'required|string|exists:payments,payment_code',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.exists' => 'Email tidak terdaftar dalam sistem.',
            'payment_code.exists' => 'Kode pembayaran tidak valid.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
        ]);

        try {
            $user = User::where('email', $request->email)->firstOrFail();

            
            $payment = Payment::where('payment_code', $request->payment_code)
                ->where('is_guest_payment', true)
                ->first();

            if (!$payment || !$payment->muzakki || $payment->muzakki->email !== $request->email) {
                return back()->with('error', 'Validasi gagal. Kode transaksi tidak cocok dengan email ini.');
            }

            DB::transaction(function () use ($user, $request) {
                $user->update([
                    'password' => Hash::make($request->password),
                    'is_active' => true,
                ]);
            });

            Auth::login($user);

            return redirect()->route('dashboard')->with('success', 'Akun berhasil diaktifkan. Selamat datang di Dashboard Muzakki.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat mengaktifkan akun. Silakan coba kembali.');
        }
    }
}
