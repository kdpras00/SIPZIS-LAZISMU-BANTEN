<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Muzakki;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    
    protected function authenticated(Request $request, $user)
    {
        if ($user->role === 'muzakki') {
            $muzakki = Muzakki::where('user_id', $user->id)->first();

            if ($muzakki && (empty($muzakki->campaign_url) || !$muzakki->campaign_url)) {
                $muzakki->campaign_url = url('/campaigner/' . $muzakki->email);
                $muzakki->save();
            }
        }
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function showAdminLogin()
    {
        return view('auth.admin-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        
        $recaptchaToken = $request->input('g-recaptcha-response');
        if (!$recaptchaToken) {
            return back()->withErrors(['email' => 'Validasi reCAPTCHA diperlukan.'])->withInput();
        }

        try {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => config('services.recaptcha.secret_key'),
                'response' => $recaptchaToken,
                'remoteip' => $request->ip(),
            ]);
            $verification = $response->json();

            if (!($verification['success'] ?? false)) {
                return back()->withErrors(['email' => 'Verifikasi reCAPTCHA gagal.'])->withInput();
            }

            
            $score = (float) ($verification['score'] ?? 0);
            $action = $verification['action'] ?? null;
            $threshold = (float) config('services.recaptcha.threshold', 0.5);
            if ($score < $threshold || ($action && $action !== 'login')) {
                return back()->withErrors(['email' => 'Aktivitas mencurigakan terdeteksi. Coba lagi.'])->withInput();
            }
        } catch (\Throwable $e) {
            return back()->withErrors(['email' => 'Layanan reCAPTCHA tidak tersedia. Coba lagi.'])->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            
            if (!$user->hasVerifiedEmail()) {
                
                return redirect()->route('verification.notice')
                    ->with('warning', 'Silakan verifikasi email Anda terlebih dahulu untuk melanjutkan.');
            }

            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Akun Anda tidak aktif. Silakan hubungi administrator.']);
            }

            
            if ($user->role !== 'muzakki') {
                Auth::logout();
                return back()->withErrors(['email' => 'Halaman ini hanya untuk muzakki. Silakan gunakan halaman login admin.']);
            }

            
            if ($user->hasTwoFactorEnabled()) {
                
                $request->session()->put('login.id', $user->id);
                Auth::logout();
                
                
                return redirect()->route('two-factor.verify');
            }

            
            if ($user->role === 'muzakki') {
                $muzakki = Muzakki::where('user_id', $user->id)->first();

                if ($muzakki && (empty($muzakki->campaign_url) || !$muzakki->campaign_url)) {
                    $muzakki->campaign_url = url('/campaigner/' . $muzakki->email);
                    $muzakki->save();
                }

                
                if ($muzakki) {
                    $hasAccountNotification = \App\Models\Notification::where('muzakki_id', $muzakki->id)
                        ->where('type', 'account')
                        ->exists();

                    
                    if (!$hasAccountNotification) {
                        try {
                            \App\Models\Notification::createAccountNotification($user, 'profile', $muzakki);
                            Log::info('Welcome notification created for first login', [
                                'user_id' => $user->id,
                                'muzakki_id' => $muzakki->id
                            ]);
                        } catch (\Exception $e) {
                            Log::error('Failed to create welcome notification on first login: ' . $e->getMessage());
                        }
                    }
                }
            }

            $request->session()->regenerate();

            
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ]);
    }

    public function adminLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        
        $recaptchaToken = $request->input('g-recaptcha-response');
        if (!$recaptchaToken) {
            return back()->withErrors(['email' => 'Validasi reCAPTCHA diperlukan.'])->withInput();
        } else {
             try {
                $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => config('services.recaptcha.secret_key'),
                    'response' => $recaptchaToken,
                    'remoteip' => $request->ip(),
                ]);
                $verification = $response->json();

                if (!($verification['success'] ?? false)) {
                     
                     
                     return back()->withErrors(['email' => 'Verifikasi reCAPTCHA gagal.'])->withInput();
                }

                
                $score = (float) ($verification['score'] ?? 0);
                $action = $verification['action'] ?? null;
                $threshold = (float) config('services.recaptcha.threshold', 0.5);
                
                
                if ($score < $threshold) { 
                    return back()->withErrors(['email' => 'Aktivitas mencurigakan terdeteksi (Score rendah).'])->withInput();
                }
            } catch (\Throwable $e) {
                
                
                return back()->withErrors(['email' => 'Layanan reCAPTCHA tidak tersedia.'])->withInput();
            }
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Akun Anda tidak aktif. Silakan hubungi administrator.']);
            }

            
            if ($user->role !== 'admin') {
                Auth::logout();
                return back()->withErrors(['email' => 'Anda tidak memiliki akses ke halaman admin.']);
            }

            
            if ($user->hasTwoFactorEnabled()) {
                
                $request->session()->put('login.id', $user->id);
                $request->session()->save(); 
                Auth::logout();
                return redirect()->route('two-factor.verify');
            }

            
            $request->session()->regenerate();
            return redirect()->route('dashboard.two-factor.setup')
                ->with('warning', 'Keamanan Wajib: Mohon aktifkan Autentikasi Dua Faktor (2FA) untuk melanjutkan akses admin.');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ]);
    }

    public function showRegister()
    {
        
        $prefillEmail = session('registered_email', '');

        return view('auth.register', compact('prefillEmail'));
    }

    public function register(Request $request)
    {
        
        $recaptchaToken = $request->input('g-recaptcha-response');
        if (!$recaptchaToken) {
            return back()->withErrors(['email' => 'Validasi reCAPTCHA diperlukan.'])->withInput();
        }

        try {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => config('services.recaptcha.secret_key'),
                'response' => $recaptchaToken,
                'remoteip' => $request->ip(),
            ]);
            $verification = $response->json();

            if (!($verification['success'] ?? false)) {
                return back()->withErrors(['email' => 'Verifikasi reCAPTCHA gagal.'])->withInput();
            }

            
            $score = (float) ($verification['score'] ?? 0);
            $action = $verification['action'] ?? null;
            $threshold = (float) config('services.recaptcha.threshold', 0.5);
            if ($score < $threshold || ($action && $action !== 'register')) {
                return back()->withErrors(['email' => 'Aktivitas mencurigakan terdeteksi. Coba lagi.'])->withInput();
            }
        } catch (\Throwable $e) {
            return back()->withErrors(['email' => 'Layanan reCAPTCHA tidak tersedia. Coba lagi.'])->withInput();
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20|unique:users', 
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.unique' => 'Email sudah terdaftar.',
            'phone.unique' => 'Nomor telepon sudah terdaftar.', 
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            
            $fullPhone = null;
            if ($request->phone) {
                $countryCode = $request->country_code ?? '+62';
                
                $countryCode = str_replace('+', '', $countryCode);
                
                $phone = ltrim($request->phone, '0');
                $fullPhone = $countryCode . $phone;
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'muzakki',
                'is_active' => true,
                'phone' => $fullPhone,
            ]);

            
            $campaignUrl = url('/campaigner/' . $request->email);

            $muzakki = Muzakki::updateOrCreate(
                ['email' => $request->email],
                [
                    'name' => $request->name,
                    'phone' => $request->phone ?? null,
                    'nik' => $request->nik ?? null,
                    'gender' => $request->gender ?? null,
                    'address' => $request->address ?? null,
                    'city' => $request->city ?? null,
                    'province' => $request->province ?? null,
                    'occupation' => $request->occupation ?? null,
                    'monthly_income' => $request->monthly_income ?? null,
                    'date_of_birth' => $request->date_of_birth ?? null,
                    'user_id' => $user->id,
                    'is_active' => true,
                    'campaign_url' => $campaignUrl, 
                ]
            );

            
            $user->refresh();

            
            try {
                Mail::to($user->email)->send(new \App\Mail\WelcomeMail($user));
            } catch (\Exception $e) {
                
                Log::error('Failed to send welcome email: ' . $e->getMessage());
            }

            
            try {
                
                if ($muzakki && $muzakki->user_id === $user->id) {
                    
                    \App\Models\Notification::createAccountNotification($user, 'profile', $muzakki);
                    Log::info('Welcome notification created for new user', [
                        'user_id' => $user->id,
                        'muzakki_id' => $muzakki->id
                    ]);
                } else {
                    Log::warning('Muzakki not properly linked to user during registration', [
                        'user_id' => $user->id,
                        'muzakki_id' => $muzakki->id ?? null,
                        'muzakki_user_id' => $muzakki->user_id ?? null
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Failed to create welcome notification: ' . $e->getMessage(), [
                    'user_id' => $user->id,
                    'muzakki_id' => $muzakki->id ?? null,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }

            
            try {
                $user->sendEmailVerificationNotification();
                Log::info('Email verification sent to: ' . $user->email);
            } catch (\Exception $e) {
                Log::error('Failed to send verification email: ' . $e->getMessage());
            }

            
            Auth::login($user);

            session(['registered_email' => $request->email]);

            return redirect()->route('verification.notice')
                ->with('success', 'Registrasi berhasil! Silakan cek email Anda untuk verifikasi akun.');
        } catch (\Exception $e) {
            if (isset($user)) {
                $user->delete();
            }

            return back()->withErrors([
                'email' => 'Registrasi gagal: ' . $e->getMessage(),
            ])->withInput();
        }
    }

    public function logout(Request $request)
    {
        
        $user = Auth::user();
        $role = $user ? $user->role : null;

        
        Auth::logout();

        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        
        if ($role === 'admin') {
            return redirect()->route('admin.login')->with('success', 'Anda telah berhasil logout.');
        }

        
        return redirect('/')->with('success', 'Anda telah berhasil logout.');
    }

    
    public function firebaseLogin(Request $request)
    {
        
        
        
        
        
        $request->validate([
            'uid' => 'required|string',
            'email' => 'required|email',
            'name' => 'required|string',
            'phone' => 'nullable|string',
            'avatar' => 'nullable|string'
        ]);

        try {
            
            $user = User::firstOrCreate(
                ['email' => $request->email],
                [
                    'name' => $request->name,
                    'password' => Hash::make(uniqid()), 
                    'role' => 'muzakki',
                    'is_active' => true,
                    'phone' => $request->phone ?? null,
                ]
            );

            
            $isNewUser = $user->wasRecentlyCreated;

            
            if ($request->filled('avatar') && $user->avatar !== $request->avatar) {
                $user->avatar = $request->avatar;
                $user->save();
            }

            
            
            $campaignUrl = url('/campaigner/' . $request->email);

            $muzakki = Muzakki::updateOrCreate(
                ['email' => $request->email],
                [
                    'name' => $request->name,
                    'phone' => $request->phone ?? null,
                    'user_id' => $user->id,
                    'is_active' => true,
                    'campaign_url' => $campaignUrl, 
                ]
            );

            
            $user->refresh();

            
            if ($isNewUser) {
                try {
                    Mail::to($user->email)->send(new \App\Mail\WelcomeMail($user));
                    Log::info('Welcome email sent to Firebase user: ' . $user->email);
                } catch (\Exception $e) {
                    
                    Log::error('Failed to send welcome email to Firebase user: ' . $e->getMessage());
                }

                
                try {
                    
                    if ($muzakki && $muzakki->user_id === $user->id) {
                        
                        \App\Models\Notification::createAccountNotification($user, 'profile', $muzakki);
                        Log::info('Welcome notification created for Firebase user', [
                            'user_id' => $user->id,
                            'muzakki_id' => $muzakki->id
                        ]);
                    } else {
                        Log::warning('Muzakki not properly linked to user', [
                            'user_id' => $user->id,
                            'muzakki_id' => $muzakki->id ?? null,
                            'muzakki_user_id' => $muzakki->user_id ?? null
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to create welcome notification for Firebase user: ' . $e->getMessage(), [
                        'user_id' => $user->id,
                        'muzakki_id' => $muzakki->id ?? null,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }

            if (!$user->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun Anda tidak aktif. Silakan hubungi administrator.'
                ], 403);
            }

            if ($user->role !== 'muzakki') {
                return response()->json([
                    'success' => false,
                    'message' => 'Login Google hanya tersedia untuk akun muzakki.'
                ], 403);
            }

            
            if ($user->hasTwoFactorEnabled()) {
                $request->session()->put('login.id', $user->id);
                Auth::logout();

                return response()->json([
                    'success' => true,
                    'two_factor_required' => true,
                    'redirect' => route('two-factor.verify'),
                    'message' => 'Autentikasi dua faktor diperlukan.'
                ]);
            }

            
            Auth::login($user);
            $request->session()->regenerate();

            return response()->json([
                'success' => true,
                'redirect' => '/',
                'message' => 'Login berhasil.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication failed: ' . $e->getMessage()
            ], 500);
        }
    }

    
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    
    public function sendPasswordResetEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        
        $recaptchaToken = $request->input('g-recaptcha-response');
        if ($recaptchaToken && config('services.recaptcha.secret_key')) {
            try {
                $verification = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => config('services.recaptcha.secret_key'),
                    'response' => $recaptchaToken,
                    'remoteip' => $request->ip(),
                ])->json();

                if (!($verification['success'] ?? false)) {
                    Log::warning('reCAPTCHA verification failed for password reset', [
                        'email' => $request->email,
                        'verification' => $verification
                    ]);
                    
                } else {
                    $score = (float) ($verification['score'] ?? 0);
                    $action = $verification['action'] ?? null;
                    $threshold = (float) config('services.recaptcha.threshold', 0.5);

                    if ($score < $threshold) {
                        Log::warning('reCAPTCHA score too low for password reset', [
                            'email' => $request->email,
                            'score' => $score,
                            'threshold' => $threshold
                        ]);
                        
                    }
                }
            } catch (\Throwable $e) {
                Log::error('reCAPTCHA verification failed: ' . $e->getMessage());
                
            }
        }

        
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            
            return back()->with('status', 'Jika email terdaftar, link reset password telah dikirim ke email Anda.');
        }

        
        $token = Str::random(64);
        
        
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => Hash::make($token),
                'created_at' => now()
            ]
        );

        
        try {
            Mail::to($user->email)->send(new \App\Mail\PasswordReset($user, $token));
            Log::info('Password reset email sent to: ' . $user->email);
        } catch (\Exception $e) {
            Log::error('Failed to send password reset email', [
                'email' => $user->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            
            if (config('mail.default') !== 'log') {
                try {
                    
                    $originalMailer = config('mail.default');
                    config(['mail.default' => 'log']);
                    
                    Mail::to($user->email)->send(new \App\Mail\PasswordReset($user, $token));
                    Log::info('Password reset email sent via log driver to: ' . $user->email);
                    Log::info('Password reset link: ' . url('password/reset', $token));
                    
                    
                    config(['mail.default' => $originalMailer]);
                    
                    
                    $resetLink = url('password/reset', $token);
                    return back()->with('status', 'Link reset password telah dibuat. Karena SMTP Gmail gagal, email disimpan di log. Link reset password: <a href="' . $resetLink . '" class="text-green-600 underline">' . $resetLink . '</a>');
                } catch (\Exception $logException) {
                    Log::error('Failed to send email even with log driver: ' . $logException->getMessage());
                    
                    return back()->with('status', 'Jika email terdaftar, link reset password telah dikirim ke email Anda.');
                }
            }
            
            
            if (config('app.debug')) {
                return back()->withErrors(['email' => 'Gagal mengirim email: ' . $e->getMessage() . '. Silakan cek konfigurasi MAIL di .env atau gunakan MAIL_MAILER=log untuk development.'])->withInput();
            }
            
            
            return back()->withErrors(['email' => 'Gagal mengirim email. Silakan coba lagi nanti atau hubungi admin.'])->withInput();
        }

        return back()->with('status', 'Jika email terdaftar, link reset password telah dikirim ke email Anda.');
    }

    
    public function showResetPassword($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$resetRecord) {
            return back()->withErrors(['email' => 'Token reset password tidak valid atau sudah kedaluwarsa.'])->withInput();
        }

        
        $createdAt = \Carbon\Carbon::parse($resetRecord->created_at);
        if ($createdAt->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['email' => 'Token reset password sudah kedaluwarsa. Silakan request reset password baru.'])->withInput();
        }

        
        if (!Hash::check($request->token, $resetRecord->token)) {
            return back()->withErrors(['email' => 'Token reset password tidak valid.'])->withInput();
        }

        
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak terdaftar.'])->withInput();
        }

        
        $user->password = Hash::make($request->password);
        $user->save();

        
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        Log::info('Password reset successful for user: ' . $user->email);

        return redirect()->route('login')->with('success', 'Password berhasil direset. Silakan login dengan password baru.');
    }
}
