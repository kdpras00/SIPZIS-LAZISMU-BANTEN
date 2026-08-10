@extends('layouts.main')

@section('content')
<div class="min-h-screen flex items-center justify-center" style="background: #faf8f5;">
    <div class="w-full max-w-md px-6">
        <div class="bg-white rounded-2xl p-8" style="box-shadow: 0 1px 3px rgba(28,15,10,0.06), 0 4px 16px rgba(28,15,10,0.06);">
            
            <div class="text-center mb-8">
                <div class="mx-auto w-14 h-14 rounded-2xl flex items-center justify-center mb-4" style="background: #fff7ed;">
                    <i class="bi bi-shield-lock text-2xl" style="color: #c2410c;"></i>
                </div>
                <h1 class="text-xl font-bold mb-1" style="color: #1c0f0a;">Verifikasi Dua Faktor</h1>
                <p class="text-sm" style="color: #8b7e74;">Masukkan kode 6 digit dari Google Authenticator</p>
            </div>

            
            <form method="POST" action="{{ route('two-factor.verify.post') }}" class="space-y-4" id="verifyForm">
                @csrf

                
                <div>
                    <input id="code" 
                           type="text" 
                           class="w-full px-4 py-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent text-center text-3xl tracking-widest font-mono @error('code') border-red-500 @enderror" 
                           name="code" 
                           placeholder="000000" 
                           maxlength="6"
                           pattern="[0-9]{6}"
                           required 
                           autocomplete="off"
                           autofocus>
                    @error('code')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                
                <button type="submit"
                        class="w-full bg-orange-600 hover:bg-orange-700 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200">
                    Verifikasi
                </button>
            </form>

            
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Tidak memiliki akses ke aplikasi authenticator?<br>
                    <a href="{{ route('login') }}" class="text-orange-600 hover:text-orange-700 font-medium">Kembali ke halaman login</a>
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const codeInput = document.getElementById('code');
        
        // Format input to only accept numbers
        codeInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // Focus on input
        codeInput.focus();
    });
</script>
@endpush
@endsection

