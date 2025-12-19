<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AccountClaimController extends Controller
{
    /**
     * Handle the account claim request (setting password for guest user).
     */
    public function claim(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $user = User::where('email', $request->email)->firstOrFail();

            // Security check: Only allow claiming if the user was created as a guest (muzakki role)
            // and potentially check if they haven't set a password yet or it was auto-generated
            // For now, we rely on the fact that this form is only shown to users who just donated
            // and we might want to add a check if the user is already "active" in a certain way,
            // but since we are just setting a password for an existing email found in the donation,
            // we should be careful. 
            
            // BETTER SECURITY: Verify the payment code matches the user
            $paymentCode = $request->input('payment_code');
            $payment = \App\Models\ZakatPayment::where('payment_code', $paymentCode)
                        ->where('status', 'completed')
                        ->first();

            if (!$payment || !$payment->muzakki || $payment->muzakki->email !== $request->email) {
                 return back()->with('error', 'Validasi gagal. Data donasi tidak cocok.');
            }

            // Update password and login
            DB::transaction(function () use ($user, $request) {
                $user->update([
                    'password' => Hash::make($request->password),
                    'is_active' => true, // Ensure account is active
                ]);
            });

            // Login the user
            Auth::login($user);

            return redirect()->route('dashboard')->with('success', 'Akun berhasil diaktifkan! Selamat datang di Dashboard Muzakki.');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat mengaktifkan akun. Silakan coba lagi.');
        }
    }
}
