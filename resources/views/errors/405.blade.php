@extends('layouts.main')

@section('title', 'Method Not Allowed')

@section('content')
<section class="min-h-screen bg-gradient-to-br from-rose-50 via-white to-pink-50 flex items-center justify-center p-6">
    <div
        class="max-w-4xl w-full bg-white/90 backdrop-blur border border-rose-100 rounded-3xl shadow-xl px-10 py-12 text-center relative overflow-hidden">
        <div class="pointer-events-none absolute inset-0">
            <div class="absolute -top-12 -left-12 w-48 h-48 bg-rose-100 rounded-full opacity-60"></div>
            <div class="absolute -bottom-16 -right-16 w-60 h-60 bg-pink-100 rounded-full opacity-60"></div>
        </div>

        <div class="relative">
            <div class="mx-auto w-32 h-32 rounded-full bg-rose-50 border border-rose-200 flex items-center justify-center mb-6">
                
                <svg viewBox="0 0 24 24" fill="none" class="w-16 h-16 text-rose-500" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                </svg>
            </div>

            <p class="text-sm uppercase tracking-[0.4em] font-semibold text-rose-500 mb-4">Error 405</p>
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-4">Metode Tidak Diizinkan</h1>
            <p class="text-gray-600 max-w-2xl mx-auto leading-relaxed mb-10">
                Maaf, metode request yang digunakan tidak valid untuk halaman ini. 
                Pastikan Anda mengakses halaman dengan cara yang benar.
            </p>

            <div class="grid gap-4 md:grid-cols-2 max-w-2xl mx-auto">
                <a href="{{ url('/') }}"
                    class="inline-flex items-center justify-center px-6 py-3 rounded-2xl bg-rose-500 text-white font-semibold shadow-lg shadow-rose-200 hover:bg-rose-600 transition-colors">
                    Kembali ke Beranda
                    <i class="bi bi-arrow-right-short text-2xl ml-1"></i>
                </a>
                <button onclick="window.history.back()"
                    class="inline-flex items-center justify-center px-6 py-3 rounded-2xl border-2 border-rose-200 text-rose-700 font-semibold hover:bg-rose-50 transition-colors">
                    Kembali ke Halaman Sebelumnya
                    <i class="bi bi-arrow-left-circle text-lg ml-2"></i>
                </button>
            </div>

            <div class="mt-10 text-sm text-gray-500">
                <p>Butuh bantuan? <a href="{{ route('home') }}#contact"
                        class="text-rose-600 font-medium hover:underline">Hubungi tim Lazismu Banten</a></p>
            </div>
        </div>
    </div>
</section>
@endsection
