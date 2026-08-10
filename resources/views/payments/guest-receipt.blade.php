@extends('layouts.main')

@section('title', 'Kwitansi Pembayaran')

@section('content')
<div class="bg-gray-100 min-h-screen py-8">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            
            <div class="bg-white p-8 sm:p-12" style="border: 2px solid #333; font-family: 'Times New Roman', Times, serif; color: #000; position: relative; max-width: 800px; margin: 0 auto; background-image: radial-gradient(#e5e7eb 1px, transparent 1px); background-size: 20px 20px;">
                
                
                <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; opacity: 0.05; pointer-events: none; z-index: 1;">
                    
                </div>

                <div style="position: relative; z-index: 10;">
                    
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 3px double #333; padding-bottom: 15px; margin-bottom: 25px;">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <img src="{{ asset('img/logo.png') }}" alt="Logo Lazismu" style="height: 70px; object-fit: contain;">
                        </div>
                        <div style="text-align: right; font-family: Arial, sans-serif;">
                            <div style="font-size: 16px; letter-spacing: 1px;"><strong>KWITANSI PEMBAYARAN</strong></div>
                            <div style="font-size: 14px; margin-top: 8px; display: flex; justify-content: flex-end; align-items: baseline; gap: 5px;">
                                <span>No.</span>
                                <span style="border-bottom: 1px dotted #333; min-width: 180px; text-align: left; padding-left: 5px; color: #c2410c; font-weight: bold; display: inline-block;">{{ $payment->receipt_number ?: $payment->payment_code }}</span>
                            </div>
                        </div>
                    </div>

                    
                    <table style="width: 100%; font-size: 16px; line-height: 2;">
                        <tr>
                            <td style="width: 25%; vertical-align: top;">Telah terima dari</td>
                            <td style="width: 2%; vertical-align: top;">:</td>
                            <td style="width: 73%; vertical-align: top; border-bottom: 1px dotted #666;">
                                {{ $payment->muzakki->name ?? 'Hamba Allah' }}
                            </td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;">Uang sejumlah</td>
                            <td style="vertical-align: top;">:</td>
                            <td style="vertical-align: top; border-bottom: 1px dotted #666; background-color: #f3f4f6; padding: 0 10px;">
                                {{ ucwords(\Illuminate\Support\Str::lower(\App\Helpers\Terbilang::convert($payment->paid_amount))) }} Rupiah
                            </td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;">Untuk pembayaran</td>
                            <td style="vertical-align: top;">:</td>
                            <td style="vertical-align: top; border-bottom: 1px dotted #666;">
                                {{ $payment->campaign ? $payment->campaign->title : ($payment->program ? $payment->program->name : ($payment->program_category ? ucfirst(str_replace('-', ' ', $payment->program_category)) : 'Donasi Umum')) }}
                                @if($payment->notes)
                                    <span>({{ $payment->notes }})</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;">Metode Pembayaran</td>
                            <td style="vertical-align: top;">:</td>
                            <td style="vertical-align: top; border-bottom: 1px dotted #666;">
                                {{ strtoupper(str_replace('_', ' ', $payment->payment_method)) }}
                            </td>
                        </tr>
                    </table>

                    
                    <div style="margin-top: 40px; display: flex; justify-content: space-between; align-items: flex-end;">
                        
                        
                        <div style="background: #fff; border: 2px solid #333; padding: 10px 30px; display: inline-block; box-shadow: 4px 4px 0 #333;">
                            <span style="font-size: 20px; font-weight: bold; font-family: Arial, sans-serif;">
                                Rp {{ number_format($payment->paid_amount, 0, ',', '.') }},-
                            </span>
                        </div>

                        
                        <div style="text-align: center; width: 250px;">
                            <div style="margin-bottom: 70px;">
                                Banten, {{ $payment->payment_date ? $payment->payment_date->format('d F Y') : date('d F Y') }}<br>
                                Penerima,
                            </div>
                            <div style="border-bottom: 1px solid #333; font-weight: bold; padding-bottom: 5px;">
                                Amil Zakat Lazismu
                            </div>
                            <div style="font-size: 12px; margin-top: 5px;">Stempel & Tanda Tangan</div>
                        </div>

                    </div>
                </div>
            </div>

            

            <div class="mt-8 mb-6 text-center no-print">
                <a href="{{ route('guest.payment.success', $payment->payment_code) }}" class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition-colors inline-flex items-center mr-4">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
                <button onclick="window.print()" class="bg-orange-600 text-white px-6 py-3 rounded-lg hover:bg-orange-700 transition-colors inline-flex items-center mr-4">
                    <i class="fas fa-print mr-2"></i>
                    Cetak Kwitansi
                </button>
                <a href="{{ route('guest.payment.receipt.download', $payment->payment_code) }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors inline-flex items-center">
                    <i class="fas fa-file-pdf mr-2"></i>
                    Unduh PDF
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .no-print {
            display: none !important;
        }

        body {
            background: white !important;
            padding: 0;
        }

        .container {
            margin: 0 !important;
            padding: 0 !important;
            max-width: 100% !important;
        }

        .shadow-lg {
            box-shadow: none !important;
        }

        .amount-section {
            break-inside: avoid;
        }
    }
</style>
@endsection