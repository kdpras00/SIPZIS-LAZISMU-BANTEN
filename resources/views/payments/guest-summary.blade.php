@extends('layouts.main')

@section('title', 'Ringkasan Pembayaran - SIPZIS Lazismu')

@section('navbar')
    @include('partials.navbarHome', ['activePage' => 'program'])
@endsection

@section('content')


<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8 flex items-center justify-center font-sans">
    {{-- Main Card: Changed max-w-md to max-w-2xl for wider layout --}}
    <div class="max-w-2xl w-full space-y-6 bg-white p-8 rounded-2xl shadow-xl border-t-4 border-emerald-500 relative overflow-hidden">
        
        {{-- Decorative Background Elements --}}
        <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 rounded-full bg-emerald-50 opacity-50 blur-xl"></div>
        <div class="absolute bottom-0 left-0 -ml-8 -mb-8 w-32 h-32 rounded-full bg-yellow-50 opacity-50 blur-xl"></div>

        <div class="relative z-10">
            {{-- Header --}}
                <!-- <div class="text-center mb-6">
                    <img class="mx-auto h-14 w-auto object-contain mb-3" src="{{ asset('img/logo.png') }}" alt="Lazismu Logo">
                </div> -->

            {{-- Amount Card (Compact) --}}
            <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-xl p-5 border border-emerald-100 shadow-sm mb-6 text-center transform transition-all hover:shadow-md">
                <p class="text-xs font-bold text-emerald-600 uppercase tracking-widest mb-1">Total Pembayaran</p>
                <div class="text-4xl font-extrabold text-gray-900 my-2 tracking-tight">
                    Rp {{ number_format($payment->paid_amount, 0, ',', '.') }}
                </div>
                
                {{-- Countdown Timer --}}
                <div id="countdown-container" class="mt-3 inline-flex items-center justify-center text-xs font-semibold text-amber-600 bg-white px-4 py-1.5 rounded-full border border-amber-100 shadow-sm">
                    <i class="far fa-clock mr-2 animate-pulse"></i>
                    <span id="countdown-timer">Memuat waktu...</span>
                </div>
                 @php 
                    $expiryTime = $payment->created_at->addHours(24); 
                    $expiryTimestamp = $expiryTime->timestamp * 1000;
                @endphp
            </div>

            {{-- Payment Methods --}}
            <div class="space-y-5">
                <h3 class="text-sm font-bold text-gray-800 flex items-center">
                    <span class="w-1 h-5 bg-emerald-500 rounded-full mr-3"></span>
                    Metode Pembayaran
                </h3>

                <div>
                    {{-- Group: E-Wallet / QRIS --}}
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1 mb-2">QRIS & E-Wallet</p>
                    {{-- Grid 4 Columns for Wallets --}}
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <button class="payment-method-btn flex flex-col items-center justify-center p-3 border border-gray-200 rounded-xl hover:bg-gray-50 hover:border-emerald-500 hover:shadow-md transition-all duration-200 group bg-white h-24" data-method="qris">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Logo_QRIS.svg" class="h-6 mb-2 filter grayscale group-hover:grayscale-0 transition-all opacity-70 group-hover:opacity-100">
                            <span class="text-[10px] font-medium text-gray-500 group-hover:text-emerald-700 transition-colors">Scan QR</span>
                        </button>
                        <button class="payment-method-btn flex flex-col items-center justify-center p-3 border border-gray-200 rounded-xl hover:bg-gray-50 hover:border-emerald-500 hover:shadow-md transition-all duration-200 group bg-white h-24" data-method="gopay">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/0/00/Logo_Gopay.svg" class="h-4 mb-3 filter grayscale group-hover:grayscale-0 transition-all opacity-70 group-hover:opacity-100">
                            <span class="text-[10px] font-medium text-gray-500 group-hover:text-emerald-700 transition-colors">GoPay</span>
                        </button>
                        <button class="payment-method-btn flex flex-col items-center justify-center p-3 border border-gray-200 rounded-xl hover:bg-gray-50 hover:border-emerald-500 hover:shadow-md transition-all duration-200 group bg-white h-24" data-method="shopeepay">
                            <img src="https://images.seeklogo.com/logo-png/40/1/shopee-pay-logo-png_seeklogo-406839.png" class="h-6 mb-2 filter grayscale group-hover:grayscale-0 transition-all opacity-70 group-hover:opacity-100">
                            <span class="text-[10px] font-medium text-gray-500 group-hover:text-emerald-700 transition-colors">ShopeePay</span>
                        </button>
                        <button class="payment-method-btn flex flex-col items-center justify-center p-3 border border-gray-200 rounded-xl hover:bg-gray-50 hover:border-emerald-500 hover:shadow-md transition-all duration-200 group bg-white h-24" data-method="dana">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/7/72/Logo_dana_blue.svg" class="h-4 mb-3 filter grayscale group-hover:grayscale-0 transition-all opacity-70 group-hover:opacity-100">
                            <span class="text-[10px] font-medium text-gray-500 group-hover:text-emerald-700 transition-colors">Dana (Link)</span>
                        </button>
                    </div>
                </div>

                {{-- Group: Banks --}}
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1 mb-2 mt-4">Virtual Account</p>
                    {{-- Grid 2 Columns for Banks --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                         @foreach(['bca_va' => ['BCA', 'https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg'], 
                                   'mandiri_va' => ['Mandiri', 'https://upload.wikimedia.org/wikipedia/commons/a/ad/Bank_Mandiri_logo_2016.svg'],
                                   'bri_va' => ['BRI', 'https://upload.wikimedia.org/wikipedia/commons/6/68/BANK_BRI_logo.svg'],
                                   'bni_va' => ['BNI', 'https://upload.wikimedia.org/wikipedia/id/5/55/BNI_logo.svg']] as $method => $details)
                        <button class="flex items-center px-4 py-3 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 hover:border-emerald-500 hover:shadow-sm payment-method-btn transition-all text-left group" data-method="{{ $method }}">
                            <img src="{{ $details[1] }}" class="h-5 w-10 object-contain mr-3 filter grayscale group-hover:grayscale-0 opacity-70 group-hover:opacity-100 transition-all">
                            <span class="text-sm font-medium text-gray-600 group-hover:text-gray-900 transition-colors">{{ $details[0] }} VA</span>
                        </button>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-50 mt-6">
                 <button id="leave-page-button"
                    class="w-full sm:w-1/3 order-2 sm:order-1 bg-white border border-gray-200 text-gray-500 font-medium py-3 rounded-xl hover:text-emerald-600 hover:border-emerald-200 hover:bg-emerald-50 transition text-sm">
                    Bayar Nanti
                </button>
                <button id="pay-button"
                    class="w-full sm:w-2/3 order-1 sm:order-2 bg-emerald-600 text-white font-bold py-3 px-6 rounded-xl hover:bg-emerald-700 transition shadow-lg shadow-emerald-200 disabled:opacity-50 disabled:cursor-not-allowed transform active:scale-[0.98] flex items-center justify-center text-base"
                    disabled>
                    Pilih Metode
                </button>
            </div>
             <p class="text-xs text-center text-gray-400 mt-2">
                Link pembayaran aman tersimpan di WhatsApp Anda.
            </p>
            
            <p class="text-center text-[10px] text-gray-300 mt-6">
                &copy; {{ date('Y') }} SIPZIS Lazismu.
            </p>
        </div>
    </div>
</div>

{{-- Midtrans Snap --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Configuration
        const isProduction = {{ config('midtrans.is_production') ? 'true' : 'false' }};
        const clientKey = '{{ config('midtrans.client_key') }}';

        const snapScript = document.createElement('script');
        snapScript.src = isProduction ?
            'https://app.midtrans.com/snap/snap.js' :
            'https://app.sandbox.midtrans.com/snap/snap.js';
        snapScript.setAttribute('data-client-key', clientKey);
        snapScript.onload = function() { console.log('Snap loaded'); };
        snapScript.onerror = function() {
            Swal.fire('Error', 'Gagal memuat sistem pembayaran. Silakan refresh.', 'error');
        };
        document.head.appendChild(snapScript);

        // Countdown Logic
        const expiryTimestamp = {{ $expiryTimestamp }};
        const countdownEl = document.getElementById('countdown-timer');
        
        function updateCountdown() {
            const now = new Date().getTime();
            const distance = expiryTimestamp - now;

            if (distance < 0) {
                clearInterval(countdownInterval);
                countdownEl.innerHTML = "WAKTU HABIS";
                countdownEl.parentElement.classList.add('bg-red-50', 'border-red-100', 'text-red-700');
                countdownEl.parentElement.classList.remove('bg-amber-50', 'border-amber-100', 'text-amber-600');
                
                Swal.fire({
                    icon: 'error',
                    title: 'Waktu Habis',
                    text: 'Batas waktu pembayaran telah habis.',
                    confirmButtonText: 'Kembali',
                    allowOutsideClick: false
                }).then(() => {
                    window.location.reload();
                });
                return;
            }

            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            countdownEl.innerHTML = `Sisa ${hours}j ${minutes}m ${seconds}s`;
        }

        const countdownInterval = setInterval(updateCountdown, 1000);
        updateCountdown();

        // Payment Logic
        const payButton = document.getElementById('pay-button');
        let selectedMethod = null;
        let isProcessing = false;

        document.querySelectorAll('.payment-method-btn').forEach(button => {
            button.addEventListener('click', function() {
                // Reset styles
                document.querySelectorAll('.payment-method-btn').forEach(btn => {
                    btn.classList.remove('border-emerald-500', 'bg-emerald-50', 'ring-2', 'ring-emerald-100', 'shadow-md');
                    btn.classList.add('border-gray-200');
                    const img = btn.querySelector('img');
                    if(img) {
                        img.classList.add('grayscale', 'opacity-70');
                        img.classList.remove('grayscale-0', 'opacity-100');
                    }
                });

                // Set Active
                this.classList.remove('border-gray-200');
                this.classList.add('border-emerald-500', 'bg-emerald-50', 'ring-2', 'ring-emerald-100', 'shadow-md');
                const img = this.querySelector('img');
                if(img) {
                    img.classList.remove('grayscale', 'opacity-70');
                    img.classList.add('grayscale-0', 'opacity-100');
                }

                selectedMethod = this.getAttribute('data-method');
                payButton.disabled = false;
                payButton.innerHTML = `Bayar Sekarang <i class="fas fa-chevron-right ml-2 text-xs"></i>`;
            });
        });

        payButton.addEventListener('click', function() {
            if (!selectedMethod) {
                Swal.fire('Pilih Metode', 'Silakan pilih metode pembayaran.', 'warning');
                return;
            }
            if (isProcessing) return;

            isProcessing = true;
            const originalText = payButton.innerHTML;
            payButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';
            payButton.disabled = true;

            fetch('{{ route('guest.payment.getTokenCustom', $payment->payment_code) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ method: selectedMethod })
            })
            .then(res => res.json())
            .then(data => {
                if(data.snap_token) {
                     if (typeof snap === 'undefined') {
                        throw new Error('Snap belum siap');
                     }
                     snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: 'Pembayaran Berhasil!',
                                showConfirmButton: false,
                                timer: 2000,
                                timerProgressBar: true
                            }).then(() => {
                                window.location.href = '{{ route('guest.payment.success', $payment->payment_code) }}';
                            });
                        },
                        onPending: function(result) {
                            Swal.fire('Pending', 'Menunggu pembayaran...', 'info').then(() => {
                                window.location.reload();
                            });
                        },
                        onError: function(result) {
                            Swal.fire('Gagal', 'Pembayaran gagal.', 'error');
                        },
                        onClose: function() {
                            // User closed popup
                        }
                     });
                } else {
                    throw new Error(data.message || 'Gagal mendapatkan token');
                }
            })
            .catch(err => {
                Swal.fire('Error', err.message, 'error');
            })
            .finally(() => {
                isProcessing = false;
                payButton.disabled = false;
                payButton.innerHTML = originalText;
            });
        });

        const leavePageButton = document.getElementById('leave-page-button');
        if(leavePageButton) {
            leavePageButton.addEventListener('click', function() {
                 fetch('{{ route('guest.payment.leavePage', $payment->payment_code) }}', {
                    method: 'POST',
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                 });
                 Swal.fire({
                     icon: 'info',
                     title: 'Link Tersimpan',
                     text: 'Silakan cek WhatsApp/Email untuk melanjutkan nanti.',
                     showConfirmButton: false,
                     timer: 2000
                 }).then(() => {
                     window.location.href = '{{ route('home') }}';
                 });
            });
        }
    });
</script>
@endsection
