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

                <div class="mt-6">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">Atau masuk dengan</span>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <button type="button" id="googleLoginBtn" class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                            <svg class="h-5 w-5 mr-2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                            Google
                        </button>
                    </div>
                </div>

                <p class="text-center text-sm text-gray-600 mt-6">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-orange-600 hover:text-orange-700 font-medium">
                        Daftar
                    </a>
                </p>
            </div>
        </div>
    </div>

    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-app.js";
        import { getAuth, signInWithPopup, GoogleAuthProvider } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-auth.js";

        const firebaseConfig = {
            apiKey: "{{ config('services.firebase.api_key') }}",
            authDomain: "{{ config('services.firebase.auth_domain') }}",
            projectId: "{{ config('services.firebase.project_id') }}",
            storageBucket: "{{ config('services.firebase.storage_bucket') }}",
            messagingSenderId: "{{ config('services.firebase.messaging_sender_id') }}",
            appId: "{{ config('services.firebase.app_id') }}",
            measurementId: "{{ config('services.firebase.measurement_id') }}"
        };

        const app = initializeApp(firebaseConfig);
        const auth = getAuth(app);
        const provider = new GoogleAuthProvider();

        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const password = document.getElementById('password');
            const icon = togglePassword ? togglePassword.querySelector('i') : null;
            const loginForm = document.getElementById('loginForm');
            const googleLoginBtn = document.getElementById('googleLoginBtn');

            // Toggle password logic
            if (togglePassword && password && icon) {
                togglePassword.addEventListener('click', function() {
                    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                    password.setAttribute('type', type);
                    if (type === 'text') {
                        icon.classList.remove('bi-eye');
                        icon.classList.add('bi-eye-slash');
                    } else {
                        icon.classList.remove('bi-eye-slash');
                        icon.classList.add('bi-eye');
                    }
                });
            }

            // ReCaptcha logic
            if (loginForm) {
                loginForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    grecaptcha.ready(function() {
                        grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', {action: 'login'}).then(function(token) {
                            let input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'g-recaptcha-response';
                            input.value = token;
                            loginForm.appendChild(input);
                            loginForm.submit();
                        });
                    });
                });
            }

            // Google Login Logic
            if (googleLoginBtn) {
                googleLoginBtn.addEventListener('click', function() {
                    const originalText = googleLoginBtn.innerHTML;
                    googleLoginBtn.disabled = true;
                    googleLoginBtn.innerHTML = 'Loading...';
                    
                    signInWithPopup(auth, provider)
                        .then((result) => {
                            return result.user.getIdToken();
                        })
                        .then((idToken) => {
                            return fetch('{{ route('firebase.login') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({ token: idToken })
                            });
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                window.location.href = data.redirect;
                            } else {
                                alert(data.message || 'Login gagal.');
                                googleLoginBtn.disabled = false;
                                googleLoginBtn.innerHTML = originalText;
                            }
                        })
                        .catch((error) => {
                            console.error('Error during Google login:', error);
                            alert('Terjadi kesalahan saat login dengan Google: ' + error.message);
                            googleLoginBtn.disabled = false;
                            googleLoginBtn.innerHTML = originalText;
                        });
                });
            }
        });
    </script>
@endsection
