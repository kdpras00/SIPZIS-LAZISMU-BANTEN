<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Muzakki;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\WhatsAppService;

class OTPController extends Controller
{
    protected $whatsAppService;

    public function __construct(WhatsAppService $whatsAppService)
    {
        $this->whatsAppService = $whatsAppService;
    }

    
    public function sendOTP(Request $request)
    {
        try {
            Log::info('sendOTP request received', ['input' => $request->all()]);

            
            $phone = $request->input('phone');
            if (substr($phone, 0, 1) === '+') {
                $phone = substr($phone, 1);
            }

            
            $request->merge(['phone' => $phone]);
            $validated = $request->validate([
                'phone' => ['required', 'regex:/^62\d{8,15}$/'], 
            ]);

            Log::info('sendOTP phone validation passed', ['phone' => $phone]);

            $otp = rand(1000, 9999);

            
            Session::put('otp_code', $otp);
            Session::put('otp_phone', $phone);
            Session::put('otp_expires', now()->addMinutes(5));

            Log::info('sendOTP session data stored', [
                'otp_code' => $otp,
                'otp_phone' => $phone,
                'otp_expires' => Session::get('otp_expires')
            ]);

            
            $message = "Kode OTP Anda adalah *{$otp}*. Berlaku 5 menit.";
            $result = $this->whatsAppService->sendMessage($phone, $message);

            Log::info('sendOTP WhatsApp result', [
                'success' => $result['success'],
                'message' => $result['message'] ?? null,
                'response' => $result['response'] ?? null
            ]);

            if ($result['success']) {
                
                return response()->json([
                    'success' => true,
                    'message' => 'Kode OTP telah dikirim ke WhatsApp Anda.'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? 'Gagal mengirim OTP. Silakan coba lagi.'
                ]);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('sendOTP validation error', [
                'errors' => $e->errors()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Format nomor telepon tidak valid. Pastikan nomor diawali dengan 62 diikuti 8-15 digit angka (contoh: 6281234567890)'
            ], 422);
        } catch (\Exception $e) {
            Log::error('sendOTP error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengirim OTP. Silakan coba lagi.'
            ], 500);
        }
    }

    
    public function verifyOTP(Request $request)
    {
        try {
            Log::info('verifyOTP request received', ['input' => $request->all()]);

            $validated = $request->validate(['otp' => 'required|digits:4']);

            Log::info('verifyOTP validation passed', ['otp' => $validated['otp']]);

            if (!Session::has('otp_code') || !Session::has('otp_phone')) {
                Log::warning('verifyOTP session data missing');
                return response()->json([
                    'success' => false,
                    'message' => 'Kode OTP tidak ditemukan atau sudah kadaluarsa.'
                ]);
            }

            Log::info('verifyOTP session data found', [
                'otp_code' => Session::get('otp_code'),
                'otp_phone' => Session::get('otp_phone'),
                'otp_expires' => Session::get('otp_expires')
            ]);

            
            if (now()->greaterThan(Session::get('otp_expires'))) {
                Session::forget(['otp_code', 'otp_phone', 'otp_expires']);
                Log::info('verifyOTP OTP expired');
                return response()->json([
                    'success' => false,
                    'message' => 'Kode OTP sudah kadaluarsa. Silakan kirim ulang kode.'
                ]);
            }

            
            if ($request->otp == Session::get('otp_code')) {
                $otpPhone = Session::get('otp_phone');

                
                Session::forget(['otp_code', 'otp_phone', 'otp_expires']);
                Log::info('verifyOTP successful');

                
                if (Auth::check()) {
                    $muzakki = Auth::user()->muzakki;
                    if ($muzakki) {
                        
                        $normalizedMuzakkiPhone = preg_replace('/^\+/', '', $muzakki->phone);
                        $normalizedOtpPhone = preg_replace('/^\+/', '', $otpPhone);

                        if ($normalizedMuzakkiPhone === $normalizedOtpPhone) {
                            $muzakki->phone_verified = true;
                            $muzakki->save();
                            Log::info('verifyOTP muzakki phone_verified updated', ['muzakki_id' => $muzakki->id]);
                        } else {
                            Log::warning('verifyOTP phone mismatch', [
                                'muzakki_phone' => $normalizedMuzakkiPhone,
                                'otp_phone' => $normalizedOtpPhone
                            ]);
                        }
                    }
                }

                
                return response()->json([
                    'success' => true,
                    'message' => 'Nomor WhatsApp berhasil diverifikasi.',
                    'verified' => true
                ]);
            } else {
                Log::info('verifyOTP code mismatch', [
                    'input_otp' => $request->otp,
                    'session_otp' => Session::get('otp_code')
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Kode OTP salah. Silakan coba lagi.'
                ]);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('verifyOTP validation error', [
                'errors' => $e->errors()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Kode OTP harus terdiri dari 4 digit angka.'
            ], 422);
        } catch (\Exception $e) {
            Log::error('verifyOTP error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat verifikasi OTP. Silakan coba lagi.'
            ], 500);
        }
    }

    
    public function resendOTP(Request $request)
    {
        try {
            
            if (!Session::has('otp_phone')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada permintaan OTP yang aktif.'
                ], 400);
            }

            $phone = Session::get('otp_phone');

            
            $otp = rand(1000, 9999);

            
            Session::put('otp_code', $otp);
            Session::put('otp_expires', now()->addMinutes(5));

            Log::info('resendOTP new code generated', [
                'phone' => $phone,
                'otp' => $otp
            ]);

            
            $message = "Kode OTP Anda adalah *{$otp}*. Berlaku 5 menit.";
            $result = $this->whatsAppService->sendMessage($phone, $message);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Kode OTP baru telah dikirim.'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? 'Gagal mengirim ulang OTP. Silakan coba lagi.'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('resendOTP error', [
                'message' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan. Silakan coba lagi.'
            ], 500);
        }
    }
}
