@extends('layouts.main')

@section('title', 'Page Expired')

@section('content')
<section class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50 flex items-center justify-center p-6">
    <div
        class="max-w-4xl w-full bg-white/90 backdrop-blur border border-indigo-100 rounded-3xl shadow-xl px-10 py-12 text-center relative overflow-hidden">
        <div class="pointer-events-none absolute inset-0">
            <div class="absolute -top-12 -left-12 w-48 h-48 bg-indigo-100 rounded-full opacity-60"></div>
            <div class="absolute -bottom-16 -right-16 w-60 h-60 bg-purple-100 rounded-full opacity-60"></div>
        </div>

        <div class="relative">
            <div class="mx-auto w-32 h-32 rounded-full bg-indigo-50 border border-indigo-200 flex items-center justify-center mb-6">
                
                <svg viewBox="0 0 24 24" fill="none" class="w-16 h-16 text-indigo-500" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>

            <p class="text-sm uppercase tracking-[0.4em] font-semibold text-indigo-500 mb-4">Error 419</p>
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-4">Halaman Kadaluarsa</h1>
            <p class="text-gray-600 max-w-2xl mx-auto leading-relaxed mb-10">
                Maaf, sesi Anda telah berakhir karena tidak ada aktivitas. 
                Silakan muat ulang halaman untuk melanjutkan.
            </p>

            <div class="grid gap-4 md:grid-cols-2 max-w-2xl mx-auto">
                <a href="{{ url('/') }}"
                    class="inline-flex items-center justify-center px-6 py-3 rounded-2xl bg-indigo-500 text-white font-semibold shadow-lg shadow-indigo-200 hover:bg-indigo-600 transition-colors">
                    Kembali ke Beranda
                    <i class="bi bi-arrow-right-short text-2xl ml-1"></i>
                </a>
                <button onclick="window.location.reload()"
                    class="inline-flex items-center justify-center px-6 py-3 rounded-2xl border-2 border-indigo-200 text-indigo-700 font-semibold hover:bg-indigo-50 transition-colors">
                    Muat Ulang Halaman
                    <i class="bi bi-arrow-clockwise text-lg ml-2"></i>
                </button>
            </div>

            <div class="mt-10 text-sm text-gray-500">
                <p>Butuh bantuan? <a href="{{ route('home') }}#contact"
                        class="text-indigo-600 font-medium hover:underline">Hubungi tim SIPZIS</a></p>
            </div>
        </div>
    </div>
</section>
@endsection
