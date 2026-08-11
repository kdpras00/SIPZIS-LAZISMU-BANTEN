@extends('layouts.main')

@section('content')
    <div class="min-h-screen flex items-center justify-center" style="background: #faf8f5;">
        <div class="w-full max-w-md px-6">
            <div class="bg-white rounded-2xl p-8" style="box-shadow: 0 1px 3px rgba(28,15,10,0.06), 0 4px 16px rgba(28,15,10,0.06);">

                <div class="text-center mb-8">
                    <div class="mx-auto mb-4" style="width: 120px;">
                        <img src="{{ asset('img/logo.png') }}" alt="Lazismu" class="w-full object-contain">
                    </div>
                </div>

                
                @if(session('success'))
                    <div class="mb-4 p-4 bg-orange-100 border border-orange-400 text-orange-700 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                
                <form method="POST" action="{{ route('login') }}" class="space-y-4" id="loginForm">
                    @csrf

                    
                    <div>
                        <input id="email" type="email"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('email') border-red-500 @enderror"
                            name="email" value="{{ old('email') }}" placeholder="Email" required autocomplete="email"
                            autofocus>
                        @error('email')
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    
                    <div>
                        <div class="relative">
                            <input id="password" type="password"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('password') border-red-500 @enderror pr-12"
                                name="password" placeholder="Password" required autocomplete="current-password">
                            <button
                                class="absolute inset-y-0 right-0 flex items-center pr-3 bg-transparent border-0 text-gray-500 cursor-pointer"
                                type="button" id="togglePassword" style=" right: 20px;">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    
                    <div class="text-right">
                        <a href="{{ route('password.request') }}" class="text-sm text-orange-600 hover:text-orange-700 font-medium">
                            Lupa Password?
                        </a>
                    </div>

                    
                    <button type="submit"
                        class="w-full bg-orange-600 hover:bg-orange-700 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200">
                        Masuk
                    </button>
                </form>


                <p class="text-center text-sm text-gray-600 mt-6">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-orange-600 hover:text-orange-700 font-medium">
                        Daftar
                    </a>
                </p>
            </div>
        </div>
    </div>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const recaptchaSiteKey = (function() {
                const fromBlade = '{{ config('services.recaptcha.site_key') }}';
                if (fromBlade && fromBlade.trim().length > 0 && fromBlade.indexOf('config(') === -1) {
                    return fromBlade.trim();
                }
                try {
                    const cfg = window.___grecaptcha_cfg || {};
                    const renderArr = cfg.render || [];
                    if (Array.isArray(renderArr) && renderArr.length > 0 && renderArr[0]) {
                        return renderArr[0];
                    }
                } catch (_) {}
                return '';
            })();
            const togglePassword = document.getElementById('togglePassword');
            const password = document.getElementById('password');
            const icon = togglePassword.querySelector('i');
            const loginForm = document.getElementById('loginForm');

        });
    </script>
@endsection
