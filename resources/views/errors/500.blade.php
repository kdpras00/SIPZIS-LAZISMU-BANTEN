@extends('layouts.main')

@section('title', 'Server Error')

@section('content')
<section class="min-h-screen bg-gradient-to-br from-red-50 via-white to-orange-50 flex items-center justify-center p-6">
    <div
        class="max-w-4xl w-full bg-white/90 backdrop-blur border border-red-100 rounded-3xl shadow-xl px-10 py-12 text-center relative overflow-hidden">
        <div class="pointer-events-none absolute inset-0">
            <div class="absolute -top-12 -left-12 w-48 h-48 bg-red-100 rounded-full opacity-60"></div>
            <div class="absolute -bottom-16 -right-16 w-60 h-60 bg-orange-100 rounded-full opacity-60"></div>
        </div>

        <div class="relative">
            <div class="mx-auto w-32 h-32 rounded-full bg-red-50 border border-red-200 flex items-center justify-center mb-6">
                <!-- Server Crash / Exclamation Icon -->
                <svg viewBox="0 0 24 24" fill="none" class="w-20 h-20 text-red-500" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
            </div>

            <p class="text-sm uppercase tracking-[0.4em] font-semibold text-red-500 mb-4">Error 500</p>
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-4">Terjadi Kesalahan Server</h1>
            <p class="text-gray-600 max-w-2xl mx-auto leading-relaxed mb-10">
                Maaf, terjadi kesalahan internal pada server kami. Kami sedang bekerja untuk memperbaikinya. 
                Silakan coba muat ulang halaman atau kembali beberapa saat lagi.
            </p>

            <div class="grid gap-4 md:grid-cols-2 max-w-2xl mx-auto">
                <a href="{{ url('/') }}"
                    class="inline-flex items-center justify-center px-6 py-3 rounded-2xl bg-red-500 text-white font-semibold shadow-lg shadow-red-200 hover:bg-red-600 transition-colors">
                    Kembali ke Beranda
                    <i class="bi bi-arrow-right-short text-2xl ml-1"></i>
                </a>
                <button onclick="window.location.reload()"
                    class="inline-flex items-center justify-center px-6 py-3 rounded-2xl border-2 border-red-200 text-red-700 font-semibold hover:bg-red-50 transition-colors">
                    Muat Ulang Halaman
                    <i class="bi bi-arrow-clockwise text-lg ml-2"></i>
                </button>
            </div>

            <div class="mt-10 text-sm text-gray-500">
                <p>Butuh bantuan? <a href="{{ route('home') }}#contact"
                        class="text-red-600 font-medium hover:underline">Hubungi tim SIPZIS</a></p>
            </div>
        </div>
    </div>
</section>
@endsection
