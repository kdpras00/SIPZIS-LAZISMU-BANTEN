@extends('layouts.main')

@section('title', 'Pembayaran Gagal')

@section('navbar')
@include('partials.navbarHome')
@endsection

@section('content')

<div class="relative bg-gradient-to-br from-red-50 via-orange-50 to-amber-50 overflow-hidden min-h-screen">
    
    <div class="absolute inset-0">
        <div class="absolute top-0 left-0 w-40 h-40 bg-red-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-pulse"></div>
        <div class="absolute top-0 right-0 w-40 h-40 bg-orange-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-pulse animation-delay-2000"></div>
        <div class="absolute -bottom-8 left-20 w-40 h-40 bg-amber-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-pulse animation-delay-4000"></div>
    </div>

    <div class="relative container mx-auto px-4 py-16">
        <div class="max-w-4xl mx-auto">
            
            <div class="text-center mb-12">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-red-500 to-orange-600 rounded-full mb-6 shadow-lg">
                    <i class="fas fa-times text-white text-4xl"></i>
                </div>
                <h1 class="text-5xl md:text-6xl font-bold bg-gradient-to-r from-red-600 to-orange-600 bg-clip-text text-transparent mb-6">
                    Pembayaran Gagal
                </h1>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto leading-relaxed">
                    Maaf, pembayaran Anda tidak dapat diproses. Silakan coba kembali atau gunakan metode pembayaran lain.
                </p>
            </div>

            
            <div class="bg-white/70 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/50 p-8 mb-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Detail Pembayaran</h2>
                    <div class="bg-red-100 text-red-800 px-4 py-2 rounded-full text-sm font-semibold">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        {{ ucfirst($payment->status ?? 'Gagal') }}
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-600">Kode Pembayaran:</span>
                            <span class="font-semibold text-red-700">{{ $payment->payment_code ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-600">Nama Donatur:</span>
                            <span class="font-semibold">{{ $payment->muzakki->name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-600">Jenis Program:</span>
                            <span class="font-semibold">{{ $payment->program ? $payment->program->name : 'Donasi Umum' }}</span>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-600">Jumlah Dibayar:</span>
                            <span class="font-semibold text-red-700">Rp {{ number_format($payment->paid_amount ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-600">Metode Pembayaran:</span>
                            <span class="font-semibold">{{ ucfirst($payment->payment_method ?? 'N/A') }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-600">Tanggal:</span>
                            <span class="font-semibold">{{ $payment->payment_date->format('d F Y') ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                @if($payment->notes ?? false)
                <div class="mt-6 p-4 bg-gray-50 rounded-xl">
                    <h4 class="font-semibold text-gray-700 mb-2">Catatan:</h4>
                    <p class="text-gray-600">{{ $payment->notes }}</p>
                </div>
                @endif
            </div>

            
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ route('guest.payment.summary', $payment->payment_code ?? '#') }}"
                    class="bg-gradient-to-r from-red-600 to-orange-600 text-white px-8 py-4 rounded-2xl transition-all duration-300 font-bold flex items-center">
                    <i class="fas fa-redo mr-2"></i>
                    Coba Lagi
                </a>

                <a href="{{ route('guest.payment.create') }}"
                    class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-8 py-4 rounded-2xl transition-all duration-300 font-bold flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Pembayaran Baru
                </a>

                <a href="{{ route('home') }}"
                    class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-8 py-4 rounded-2xl transition-all duration-300 font-bold flex items-center">
                    <i class="fas fa-home mr-2"></i>
                    Kembali ke Beranda
                </a>
            </div>

            
            <div class="mt-12 text-center">
                <div class="bg-gradient-to-r from-red-50 to-orange-50 rounded-2xl p-8 shadow-lg">
                    <h3 class="text-2xl font-bold text-red-800 mb-4">Butuh Bantuan?</h3>
                    <p class="text-gray-700 mb-6">
                        Jika Anda mengalami kesulitan dalam melakukan pembayaran, jangan ragu untuk menghubungi kami.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <div class="bg-white rounded-xl p-4 shadow">
                            <i class="fas fa-phone-alt text-red-600 text-2xl mb-2"></i>
                            <p class="font-semibold">Hotline</p>
                            <p class="text-sm text-gray-600">021-12345678</p>
                        </div>
                        <div class="bg-white rounded-xl p-4 shadow">
                            <i class="fas fa-envelope text-red-600 text-2xl mb-2"></i>
                            <p class="font-semibold">Email</p>
                            <p class="text-sm text-gray-600">support@sipzis.org</p>
                        </div>
                        <div class="bg-white rounded-xl p-4 shadow">
                            <i class="fas fa-comments text-red-600 text-2xl mb-2"></i>
                            <p class="font-semibold">Live Chat</p>
                            <p class="text-sm text-gray-600">Via Website</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .animation-delay-2000 {
        animation-delay: 2s;
    }

    .animation-delay-4000 {
        animation-delay: 4s;
    }
</style>
@endsection