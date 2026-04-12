@extends('layouts.app')

@section('page-title', 'Setup Autentikasi Dua Faktor - Dashboard Muzakki')

@section('content')
<div class="py-4 px-4 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex items-center mb-6">
        @php
            $backRoute = auth()->user()->role === 'admin' ? route('dashboard') : route('dashboard.management');
        @endphp
        <a href="{{ $backRoute }}" class="text-gray-700 mr-3 hover:text-gray-900">
            <i class="bi bi-arrow-left text-xl"></i>
        </a>
        <h5 class="text-xl font-semibold text-gray-900 mb-0">Setup Autentikasi Dua Faktor</h5>
    </div>

    @if($user->two_factor_enabled)
    <!-- Already Enabled -->
    <div class="bg-white rounded-xl shadow-md mb-6">
        <div class="p-6">
            <div class="bg-orange-50 border-l-4 border-orange-400 p-4 rounded-lg mb-4">
                <div class="flex items-start">
                    <i class="bi bi-check-circle text-orange-600 mr-2 mt-0.5"></i>
                    <div>
                        <p class="text-sm text-orange-800 m-0 font-semibold">Autentikasi Dua Faktor Aktif</p>
                        <p class="text-sm text-orange-700 m-0 mt-1">Akun Anda dilindungi dengan autentikasi dua faktor.</p>
                    </div>
                </div>
            </div>

            <!-- Disable Form -->
            <form method="POST" action="{{ route('dashboard.two-factor.disable') }}" class="mt-4">
                @csrf
                <div class="mb-4">
                    <label for="disable_code" class="block text-sm font-medium text-gray-700 mb-2">Masukkan Kode dari Aplikasi Authenticator</label>
                    <input type="text" 
                           class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all text-center text-2xl tracking-widest" 
                           id="disable_code" 
                           name="code" 
                           placeholder="000000" 
                           maxlength="6"
                           pattern="[0-9]{6}"
                           required
                           autocomplete="off">
                    <p class="text-xs text-gray-500 mt-2">Masukkan kode 6 digit dari aplikasi Google Authenticator untuk menonaktifkan 2FA</p>
                </div>
                <button type="submit" class="w-full px-5 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors shadow-md hover:shadow-lg">
                    <i class="bi bi-x-circle mr-1"></i> Nonaktifkan Autentikasi Dua Faktor
                </button>
            </form>
        </div>
    </div>
    @else
    <!-- Setup Instructions -->
    <div class="bg-white rounded-xl shadow-md mb-6">
        <div class="p-6">
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg mb-6">
                <div class="flex items-start">
                    <i class="bi bi-info-circle text-blue-600 mr-2 mt-0.5"></i>
                    <div class="text-sm text-blue-800">
                        <p class="m-0 font-semibold mb-1">Cara Mengaktifkan Autentikasi Dua Faktor:</p>
                        <ol class="list-decimal list-inside space-y-1 mt-2">
                            <li>Install aplikasi Google Authenticator di smartphone Anda</li>
                            <li>Scan QR Code di bawah ini dengan aplikasi Google Authenticator</li>
                            <li>Masukkan kode 6 digit yang muncul di aplikasi untuk verifikasi</li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- QR Code Section - PROMINENT -->
            <div class="text-center mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    📱 Scan QR Code Ini
                </h3>
                
                <!-- Larger QR Code with better styling -->
                <div class="inline-block p-6 bg-white border-4 border-orange-500 rounded-2xl shadow-lg">
                    <img src="{{ $qrCode }}" 
                         alt="QR Code" 
                         class="mx-auto" 
                         style="width: 350px; height: 350px;">
                </div>
                
                <p class="text-sm text-gray-600 mt-4 font-medium">
                    Gunakan aplikasi <strong>Google Authenticator</strong> untuk scan
                </p>
                
                <!-- App Download Links -->
                <div class="flex justify-center gap-4 mt-4 flex-wrap">
                    <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2" 
                       target="_blank" 
                       class="text-xs text-blue-600 hover:underline inline-flex items-center gap-1">
                        <i class="bi bi-google-play"></i> Download untuk Android
                    </a>
                    <a href="https://apps.apple.com/app/google-authenticator/id388497605" 
                       target="_blank" 
                       class="text-xs text-blue-600 hover:underline inline-flex items-center gap-1">
                        <i class="bi bi-apple"></i> Download untuk iOS
                    </a>
                </div>
            </div>

            <!-- Manual Entry - Secondary Option (Collapsible) -->
            <details class="bg-gray-50 rounded-lg p-4 mb-6">
                <summary class="cursor-pointer text-sm font-semibold text-gray-700 hover:text-gray-900 transition-colors">
                    ⚙️ Tidak bisa scan? Klik untuk manual entry
                </summary>
                <div class="mt-3">
                    <p class="text-xs text-gray-600 mb-2">Masukkan kode berikut secara manual:</p>
                    <div class="flex items-center justify-between bg-white p-3 rounded border border-gray-300">
                        <code class="text-sm font-mono text-gray-900">{{ $secret }}</code>
                        <button type="button" 
                                class="ml-2 px-3 py-1 text-xs bg-gray-100 hover:bg-gray-200 rounded transition-colors"
                                onclick="copyToClipboard('{{ $secret }}')">
                            <i class="bi bi-copy"></i> Copy
                        </button>
                    </div>
                </div>
            </details>

            <!-- Verification Form -->
            <form method="POST" action="{{ route('dashboard.two-factor.enable') }}">
                @csrf
                <div class="mb-4">
                    <label for="enable_code" class="block text-sm font-medium text-gray-700 mb-2">Masukkan Kode Verifikasi</label>
                    <input type="text" 
                           class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all text-center text-2xl tracking-widest @error('code') border-red-500 @enderror" 
                           id="enable_code" 
                           name="code" 
                           placeholder="000000" 
                           maxlength="6"
                           pattern="[0-9]{6}"
                           required
                           autocomplete="off">
                    @error('code')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                    <p class="text-xs text-gray-500 mt-2">Masukkan kode 6 digit dari aplikasi Google Authenticator</p>
                </div>
                <button type="submit" class="w-full px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-purple-600 to-purple-700 rounded-lg hover:from-purple-700 hover:to-purple-800 transition-all shadow-md hover:shadow-lg">
                    <i class="bi bi-check-circle mr-1"></i> Aktifkan Autentikasi Dua Faktor
                </button>
            </form>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
    // Auto-focus and format code input
    document.addEventListener('DOMContentLoaded', function() {
        const codeInputs = document.querySelectorAll('input[name="code"]');
        codeInputs.forEach(input => {
            // Format input to only accept numbers
            input.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '');
            });

            // Auto-submit when 6 digits entered
            input.addEventListener('input', function(e) {
                if (this.value.length === 6) {
                    // Optional: auto-submit after a short delay
                    // setTimeout(() => this.form.submit(), 500);
                }
            });
        });
    });

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            alert('Kode berhasil disalin!');
        }, function(err) {
            console.error('Failed to copy: ', err);
        });
    }
</script>
@endpush
@endsection

