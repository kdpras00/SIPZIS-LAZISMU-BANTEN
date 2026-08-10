@extends('layouts.main')

@section('title', 'Access Forbidden')

@section('content')
<section class="min-h-screen bg-gradient-to-br from-amber-50 via-white to-yellow-50 flex items-center justify-center p-6">
    <div
        class="max-w-4xl w-full bg-white/90 backdrop-blur border border-amber-100 rounded-3xl shadow-xl px-10 py-12 text-center relative overflow-hidden">
        <div class="pointer-events-none absolute inset-0">
            <div class="absolute -top-12 -left-12 w-48 h-48 bg-amber-100 rounded-full opacity-60"></div>
            <div class="absolute -bottom-16 -right-16 w-60 h-60 bg-yellow-100 rounded-full opacity-60"></div>
        </div>

        <div class="relative">
            <div class="mx-auto w-32 h-32 rounded-full bg-amber-50 border border-amber-200 flex items-center justify-center mb-6">
                
                <svg viewBox="0 0 24 24" fill="none" class="w-16 h-16 text-amber-500" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0V10.5m-1.5 0h12a2.25 2.25 0 012.25 2.25v6a2.25 2.25 0 01-2.25 2.25h-12a2.25 2.25 0 01-2.25-2.25v-6A2.25 2.25 0 016.75 10.5z" />
                </svg>
            </div>

            <p class="text-sm uppercase tracking-[0.4em] font-semibold text-amber-500 mb-4">Error 403</p>
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-4">Akses Ditolak</h1>
            <p class="text-gray-600 max-w-2xl mx-auto leading-relaxed mb-10">
                Maaf, Anda tidak memiliki izin untuk mengakses halaman ini. 
                Hubungi administrator jika Anda merasa ini adalah kesalahan.
            </p>

            <div class="grid gap-4 md:grid-cols-2 max-w-2xl mx-auto">
                <a href="{{ url('/') }}"
                    class="inline-flex items-center justify-center px-6 py-3 rounded-2xl bg-amber-500 text-white font-semibold shadow-lg shadow-amber-200 hover:bg-amber-600 transition-colors">
                    Kembali ke Beranda
                    <i class="bi bi-arrow-right-short text-2xl ml-1"></i>
                </a>
                <a href="{{ route('login') }}"
                    class="inline-flex items-center justify-center px-6 py-3 rounded-2xl border-2 border-amber-200 text-amber-700 font-semibold hover:bg-amber-50 transition-colors">
                    Login Ulang
                    <i class="bi bi-box-arrow-in-right text-lg ml-2"></i>
                </a>
            </div>

            <div class="mt-10 text-sm text-gray-500">
                <p>Butuh bantuan? <a href="{{ route('home') }}#contact"
                        class="text-amber-600 font-medium hover:underline">Hubungi tim SIPZIS</a></p>
            </div>
        </div>
    </div>
</section>
@endsection
