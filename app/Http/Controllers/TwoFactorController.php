<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class TwoFactorController extends Controller
{
    protected $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    
    public function showSetup()
    {
        $user = Auth::user();
        
        
        if (!$user->two_factor_secret) {
            $user->two_factor_secret = $this->google2fa->generateSecretKey();
            $user->save();
        }

        
        $qrCodeUrl = $this->google2fa->getQRCodeUrl(
            config('app.name', 'SIPZIS'),
            $user->email,
            $user->two_factor_secret
        );

        
        $renderer = new ImageRenderer(
            new RendererStyle(300),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $qrCodeSvg = $writer->writeString($qrCodeUrl);
        
        
        $qrCodeDataUri = 'data:image/svg+xml;base64,' . base64_encode($qrCodeSvg);

        return view('muzakki.dashboard.two-factor-setup', [
            'user' => $user,
            'qrCode' => $qrCodeDataUri,
            'secret' => $user->two_factor_secret
        ]);
    }

    
    public function enable(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6',
        ]);

        $user = Auth::user();

        if (!$user->two_factor_secret) {
            return back()->withErrors(['code' => 'Secret key tidak ditemukan. Silakan refresh halaman.']);
        }

        
        $valid = $this->google2fa->verifyKey(
            $user->two_factor_secret,
            $request->code
        );

        if (!$valid) {
            return back()->withErrors(['code' => 'Kode verifikasi tidak valid.']);
        }

        
        $user->two_factor_enabled = true;
        $user->two_factor_confirmed_at = now();
        $user->save();

        $redirectRoute = $user->role === 'admin' ? 'dashboard' : 'dashboard.management';

        return redirect()->route($redirectRoute)
            ->with('success', 'Autentikasi dua faktor berhasil diaktifkan!');
    }

    
    public function disable(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6',
        ]);

        $user = Auth::user();

        if (!$user->two_factor_enabled) {
            return back()->withErrors(['code' => 'Autentikasi dua faktor belum diaktifkan.']);
        }

        
        $valid = $this->google2fa->verifyKey(
            $user->two_factor_secret,
            $request->code
        );

        if (!$valid) {
            return back()->withErrors(['code' => 'Kode verifikasi tidak valid.']);
        }

        
        $user->two_factor_enabled = false;
        $user->two_factor_secret = null;
        $user->two_factor_confirmed_at = null;
        $user->save();

        $redirectRoute = $user->role === 'admin' ? 'dashboard' : 'dashboard.management';

        return redirect()->route($redirectRoute)
            ->with('success', 'Autentikasi dua faktor berhasil dinonaktifkan!');
    }

    
    public function showVerify(Request $request)
    {
        $userId = $request->session()->get('login.id');
        
        if (!$userId) {
            return redirect()->route('login')->withErrors(['message' => 'Sesi login tidak ditemukan.']);
        }

        
        $request->session()->regenerateToken();

        return view('auth.two-factor-verify');
    }

    
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6',
        ]);

        $userId = $request->session()->get('login.id');
        $user = User::find($userId);

        if (!$user || !$user->two_factor_secret) {
            return back()->withErrors(['code' => 'User tidak ditemukan atau 2FA tidak diaktifkan.']);
        }

        $valid = $this->google2fa->verifyKey(
            $user->two_factor_secret,
            $request->code
        );

        if (!$valid) {
            return back()->withErrors(['code' => 'Kode verifikasi tidak valid.']);
        }

        
        $request->session()->forget('login.id');

        
        Auth::login($user);
        $request->session()->regenerate();

        
        if ($user->role === 'admin') {
            return redirect()->route('dashboard');
        }

        return redirect()->intended('/');
    }
}
