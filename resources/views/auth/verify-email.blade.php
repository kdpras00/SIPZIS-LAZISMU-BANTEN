@extends('layouts.main')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-orange-900">
    <div class="w-full max-w-md px-6">
        <div class="bg-white rounded-lg shadow-md p-8">
            <!-- Icon & Title -->
            <div class="text-center mb-6">
                <div class="mx-auto w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Verifikasi Email Anda</h2>
                <p class="text-gray-600 text-sm">
                    Kami telah mengirimkan link verifikasi ke email Anda
                </p>
            </div>

            <!-- Instructions -->
            <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-6">
                <p class="text-sm text-orange-800 text-center">
                    <i class="bi bi-info-circle me-1"></i>
                    Silakan cek <strong>inbox</strong> atau <strong>folder spam</strong> Anda dan klik link verifikasi
                </p>
            </div>

            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-4 p-4 bg-orange-100 border border-orange-400 text-orange-700 rounded-lg text-sm">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Warning Message -->
            @if (session('warning'))
                <div class="mb-4 p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded-lg text-sm">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    {{ session('warning') }}
                </div>
            @endif

            <!-- Resend Form -->
            <form method="POST" action="{{ route('verification.send') }}" class="mb-4">
                @csrf
                <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Kirim Ulang Email Verifikasi
                </button>
            </form>

            <!-- Divider -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-4 bg-white text-gray-400">atau</span>
                </div>
            </div>

            <!-- Logout Button -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium py-3 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Logout
                </button>
            </form>

            <!-- Help Text -->
            <p class="text-center text-xs text-gray-500 mt-6">
                Tidak menerima email? Periksa folder spam atau klik tombol kirim ulang di atas
            </p>
        </div>
    </div>
</div>
@endsection
