@extends('layouts.main')

@section('content')
<div class="relative min-h-screen flex items-center justify-center overflow-hidden" style="background: #faf8f5;">

    <div class="absolute inset-0 opacity-[0.06]" style="background-image: url('{{ asset("img/masjidbanten.png") }}'); background-size: cover; background-position: center;"></div>

    <div class="relative z-10 w-full max-w-md mx-auto px-6">
        <div class="bg-white rounded-2xl p-8" style="box-shadow: 0 1px 3px rgba(28,15,10,0.06), 0 4px 16px rgba(28,15,10,0.06);">
            <div class="text-center mb-8">
                <div class="mx-auto mb-4" style="width: 120px;">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo Lazismu" class="w-full object-contain">
                </div>
                <p class="text-sm" style="color: #8b7e74;">Masuk ke dashboard administrasi</p>
            </div>

            <form method="POST" action="{{ route('admin.login') }}" class="space-y-5" id="adminLoginForm">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium mb-1.5" style="color: #1c0f0a;">Email</label>
                    <input id="email" type="email"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('email') border-red-400 @enderror"
                        name="email" value="{{ old('email') }}"
                        placeholder="admin@lazismu.org"
                        required autocomplete="email" autofocus>
                    @error('email')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium mb-1.5" style="color: #1c0f0a;">Password</label>
                    <div class="relative">
                        <input id="password" type="password"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent pr-12 @error('password') border-red-400 @enderror"
                            name="password"
                            placeholder="••••••••"
                            required autocomplete="current-password">
                        <button class="absolute inset-y-0 right-3 flex items-center bg-transparent border-0 text-gray-400 hover:text-gray-600 cursor-pointer" type="button" id="togglePassword">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    @error('password')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex items-center">
                    <input class="w-4 h-4 rounded border-gray-300 text-orange-600 focus:ring-orange-500"
                        type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="ml-2 text-sm" style="color: #8b7e74;" for="remember">Ingat saya</label>
                </div>

                <button type="submit"
                    class="w-full text-white font-semibold py-3 px-6 rounded-xl transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:ring-offset-2"
                    style="background: #c2410c;">
                    Masuk ke Dashboard
                </button>
            </form>
        </div>
    </div>

</div>

<script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        
        if (togglePassword && password) {
            const icon = togglePassword.querySelector('i');
            
            if (icon) {
                togglePassword.addEventListener('click', function() {
                    // Toggle the type attribute
                    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                    password.setAttribute('type', type);

                    // Toggle the eye icon
                    if (type === 'password') {
                        icon.classList.remove('bi-eye-slash');
                        icon.classList.add('bi-eye');
                    } else {
                        icon.classList.remove('bi-eye');
                        icon.classList.add('bi-eye-slash');
                    }
                });
            }
        }

        // reCAPTCHA Logic
        const loginForm = document.getElementById('adminLoginForm');
        const recaptchaSiteKey = '{{ config('services.recaptcha.site_key') }}';

        if (loginForm) {
            loginForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (!window.grecaptcha || !window.grecaptcha.execute) {
                    alert('Memuat reCAPTCHA... silakan coba lagi.');
                    return false;
                }

                window.grecaptcha.ready(function() {
                    window.grecaptcha.execute(recaptchaSiteKey, {
                            action: 'login'
                        })
                        .then(function(token) {
                            let tokenInput = loginForm.querySelector('input[name="g-recaptcha-response"]');
                            if (!tokenInput) {
                                tokenInput = document.createElement('input');
                                tokenInput.type = 'hidden';
                                tokenInput.name = 'g-recaptcha-response';
                                loginForm.appendChild(tokenInput);
                            }
                            tokenInput.value = token;

                            let actionInput = loginForm.querySelector('input[name="g-recaptcha-action"]');
                            if (!actionInput) {
                                actionInput = document.createElement('input');
                                actionInput.type = 'hidden';
                                actionInput.name = 'g-recaptcha-action';
                                loginForm.appendChild(actionInput);
                            }
                            actionInput.value = 'login'; // or 'admin_login' if you want to differentiate

                            loginForm.submit();
                        })
                        .catch(function(err) {
                            console.error('reCAPTCHA execute error:', err);
                            alert('Validasi reCAPTCHA gagal. Coba lagi.');
                        });
                });
            });
        }
    });
</script>
@endsection