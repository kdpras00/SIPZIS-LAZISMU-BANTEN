@extends('layouts.app')

@section('page-title', 'Autentikasi Dua Faktor (2FA) - SIPZIS Lazismu Banten')

@section('content')
<div class="py-6 px-4 max-w-3xl mx-auto">
    
    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-[#f0ece6]">
        @php
            $backRoute = auth()->user()->hasRole('admin') ? route('dashboard') : route('dashboard.management');
        @endphp
        <a href="{{ $backRoute }}" class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-white border border-[#e8e0d6] text-[#8b7e74] hover:text-[#1c0f0a] hover:bg-[#f0ece6] transition-all shadow-sm">
            <i class="bi bi-arrow-left text-lg"></i>
        </a>
        <div>
            <h1 class="text-xl font-bold text-[#1c0f0a] tracking-tight mb-0.5">Autentikasi Dua Faktor (2FA)</h1>
            <p class="text-xs text-[#8b7e74] m-0">Tingkatkan keamanan akun Anda dengan verifikasi 6 digit dari aplikasi authenticator.</p>
        </div>
    </div>

    @if($user->two_factor_enabled)
    
    <div class="bg-white rounded-2xl border border-[#f0ece6] p-6 shadow-sm mb-6">
        
        <div class="bg-emerald-50/80 border border-emerald-200/80 rounded-xl p-4 mb-6 flex items-start gap-3">
            <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center flex-shrink-0 text-emerald-700">
                <i class="bi bi-shield-check text-xl"></i>
            </div>
            <div>
                <h2 class="text-sm font-semibold text-emerald-950 m-0">Autentikasi Dua Faktor Aktif</h2>
                <p class="text-xs text-emerald-800 m-0 mt-1 leading-relaxed">
                    Akun Anda terlindungi dengan keamanan dua lapis. Setiap kali Anda login, kode unik 6 digit akan diminta.
                </p>
            </div>
        </div>

        
        <div class="pt-2 border-t border-[#f0ece6]">
            <h3 class="text-sm font-semibold text-[#1c0f0a] mb-2">Nonaktifkan 2FA</h3>
            <p class="text-xs text-[#8b7e74] mb-4">
                Jika Anda ingin mematikan fitur ini, masukkan kode 6 digit terbaru dari aplikasi Authenticator Anda untuk konfirmasi.
            </p>

            <form method="POST" action="{{ route('dashboard.two-factor.disable') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="disable_code" class="block text-xs font-medium text-[#8b7e74] mb-1.5 uppercase tracking-wider">Kode Authenticator (6 Digit)</label>
                    <input type="text" 
                           class="w-full max-w-xs px-4 py-3 rounded-xl border border-[#e8e0d6] bg-white text-center text-2xl tracking-[0.3em] font-semibold text-[#1c0f0a] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 transition-all outline-none" 
                           id="disable_code" 
                           name="code" 
                           placeholder="000000" 
                           maxlength="6"
                           pattern="[0-9]{6}"
                           required
                           inputmode="numeric"
                           autocomplete="off">
                    @error('code')
                        <span class="text-red-600 text-xs mt-1.5 block font-medium">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="inline-flex items-center justify-center px-5 py-2.5 text-xs font-medium text-red-700 bg-red-50 border border-red-200 rounded-xl hover:bg-red-100 hover:text-red-800 active:scale-[0.99] transition-all shadow-sm">
                    <i class="bi bi-shield-x mr-1.5 text-sm"></i> Nonaktifkan Autentikasi Dua Faktor
                </button>
            </form>
        </div>
    </div>
    @else
    
    <div class="bg-white rounded-2xl border border-[#f0ece6] p-6 shadow-sm mb-6 space-y-8">
        
        
        <div class="flex items-start gap-4">
            <div class="w-8 h-8 rounded-full bg-[#fff7ed] border border-[#ffedd5] text-[#c2410c] font-bold text-xs flex items-center justify-center flex-shrink-0 mt-0.5">
                1
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="text-sm font-semibold text-[#1c0f0a] m-0 mb-1">Unduh Aplikasi Authenticator</h3>
                <p class="text-xs text-[#8b7e74] leading-relaxed m-0 mb-3">
                    Unduh dan pasang aplikasi TOTP seperti <strong>Google Authenticator</strong>, <strong>Microsoft Authenticator</strong>, atau <strong>Authy</strong> pada smartphone Anda.
                </p>
                <div class="flex items-center gap-3 flex-wrap">
                    <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2" 
                       target="_blank" 
                       rel="noopener"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-[#e8e0d6] bg-[#faf8f5] text-xs font-medium text-[#1c0f0a] hover:bg-[#f0ece6] transition-colors shadow-2xs">
                        <i class="bi bi-google-play text-emerald-600"></i> Google Play (Android)
                    </a>
                    <a href="https://apps.apple.com/app/google-authenticator/id388497605" 
                       target="_blank" 
                       rel="noopener"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-[#e8e0d6] bg-[#faf8f5] text-xs font-medium text-[#1c0f0a] hover:bg-[#f0ece6] transition-colors shadow-2xs">
                        <i class="bi bi-apple text-gray-800"></i> App Store (iOS)
                    </a>
                </div>
            </div>
        </div>

        <hr class="border-[#f0ece6] m-0">

        
        <div class="flex items-start gap-4">
            <div class="w-8 h-8 rounded-full bg-[#fff7ed] border border-[#ffedd5] text-[#c2410c] font-bold text-xs flex items-center justify-center flex-shrink-0 mt-0.5">
                2
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="text-sm font-semibold text-[#1c0f0a] m-0 mb-1">Pindai QR Code</h3>
                <p class="text-xs text-[#8b7e74] leading-relaxed m-0 mb-4">
                    Buka aplikasi authenticator di smartphone Anda, pilih opsi <strong>Pindai QR Code</strong>, dan arahkan kamera ke kode di bawah ini.
                </p>
                
                
                <div class="flex flex-col sm:flex-row items-center gap-6 bg-[#faf8f5] p-5 rounded-2xl border border-[#f0ece6]">
                    <div class="bg-white p-3 rounded-xl border border-[#e8e0d6] shadow-sm flex-shrink-0">
                        <img src="{{ $qrCode }}" 
                             alt="2FA QR Code" 
                             class="w-48 h-48 object-contain">
                    </div>
                    <div class="space-y-3 text-center sm:text-left">
                        <div>
                            <span class="text-[11px] font-semibold uppercase tracking-wider text-[#8b7e74]">Alternatif Entri Manual</span>
                            <p class="text-xs text-[#8b7e74] mt-0.5">Jika kamera tidak bisa memindai, masukkan Setup Key ini secara manual:</p>
                        </div>
                        <div class="inline-flex items-center gap-2 bg-white px-3 py-2 rounded-xl border border-[#e8e0d6] shadow-2xs">
                            <code class="text-xs font-semibold text-[#1c0f0a] tracking-wider">{{ $secret }}</code>
                            <button type="button" 
                                    id="btn-copy-secret"
                                    class="p-1 text-[#8b7e74] hover:text-[#c2410c] transition-colors rounded"
                                    title="Salin Kunci"
                                    onclick="copySecretKey('{{ $secret }}')">
                                <i class="bi bi-copy text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr class="border-[#f0ece6] m-0">

        
        <div class="flex items-start gap-4">
            <div class="w-8 h-8 rounded-full bg-[#fff7ed] border border-[#ffedd5] text-[#c2410c] font-bold text-xs flex items-center justify-center flex-shrink-0 mt-0.5">
                3
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="text-sm font-semibold text-[#1c0f0a] m-0 mb-1">Verifikasi Kode 6 Digit</h3>
                <p class="text-xs text-[#8b7e74] leading-relaxed m-0 mb-4">
                    Masukkan 6 digit angka yang muncul di aplikasi Authenticator untuk menyelesaikan pengaktifan.
                </p>

                <form method="POST" action="{{ route('dashboard.two-factor.enable') }}" class="space-y-4 max-w-sm">
                    @csrf
                    <div>
                        <label for="enable_code" class="block text-xs font-medium text-[#8b7e74] mb-1.5 uppercase tracking-wider">Kode Verifikasi</label>
                        <input type="text" 
                               class="w-full px-4 py-3 rounded-xl border @error('code') border-red-500 focus:ring-red-200 @else border-[#e8e0d6] focus:border-[#c2410c] focus:ring-[#c2410c]/10 @enderror bg-white text-center text-2xl tracking-[0.3em] font-semibold text-[#1c0f0a] focus:ring-2 transition-all outline-none" 
                               id="enable_code" 
                               name="code" 
                               placeholder="000000" 
                               maxlength="6"
                               pattern="[0-9]{6}"
                               required
                               inputmode="numeric"
                               autocomplete="off">
                        @error('code')
                            <span class="text-red-600 text-xs mt-1.5 block font-medium">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="w-full inline-flex items-center justify-center px-5 py-3 text-xs font-semibold text-white bg-[#c2410c] rounded-xl hover:bg-[#9a3412] active:scale-[0.99] transition-all shadow-sm hover:shadow-md">
                        <i class="bi bi-shield-check mr-1.5 text-base"></i> Aktifkan Autentikasi Dua Faktor
                    </button>
                </form>
            </div>
        </div>

    </div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const codeInputs = document.querySelectorAll('input[name="code"]');
        codeInputs.forEach(input => {
            // Keep numeric only
            input.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        });
    });

    function copySecretKey(text) {
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text).then(() => showToast('Kunci rahasia berhasil disalin!'));
        } else {
            const textArea = document.createElement('textarea');
            textArea.value = text;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            showToast('Kunci rahasia berhasil disalin!');
        }
    }

    function showToast(msg) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: msg,
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true
            });
        } else {
            alert(msg);
        }
    }
</script>
@endpush
@endsection
