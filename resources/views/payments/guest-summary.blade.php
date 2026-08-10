    @extends('layouts.main')

    @section('title', 'Ringkasan Pembayaran - SIPZIS Lazismu')

    @section('navbar')
        
    @endsection

    @section('content')

    <div class="min-h-screen bg-gray-50 pt-8 pb-12 px-4 sm:px-6 lg:px-8 font-sans flex justify-center items-start">

        
        <div class="max-w-sm mx-auto w-full space-y-5 bg-white p-6 rounded-[1.5rem] shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] border-t-[5px] border-orange-500 relative overflow-hidden">
            
            
            <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 rounded-full bg-orange-50 opacity-50 blur-xl"></div>
            <div class="absolute bottom-0 left-0 -ml-8 -mb-8 w-32 h-32 rounded-full bg-yellow-50 opacity-50 blur-xl"></div>

            <div class="relative z-10">
                
                    

                
                <div class="bg-gradient-to-br from-orange-50 to-orange-50 rounded-xl p-3 border border-orange-100 shadow-sm mb-4 text-center transform transition-all hover:shadow-md">
                    <p class="text-[9px] font-bold text-orange-600 uppercase tracking-widest mb-1">Total Pembayaran</p>
                    <div class="text-2xl font-extrabold text-gray-900 my-1 tracking-tight">
                        Rp {{ number_format($payment->paid_amount, 0, ',', '.') }}
                    </div>
                    
                    
                    <div id="countdown-container" class="mt-3 inline-flex items-center justify-center text-xs font-semibold text-amber-600 bg-white px-4 py-1.5 rounded-full border border-amber-100 shadow-sm">
                        <i class="far fa-clock mr-2 animate-pulse"></i>
                        <span id="countdown-timer">Memuat waktu...</span>
                    </div>
                    @php 
                        $expiryTime = $payment->created_at->addHours(24); 
                        $expiryTimestamp = $expiryTime->timestamp * 1000;
                    @endphp
                </div>

                
                <div class="space-y-3">
                    <h3 class="text-xs font-bold text-gray-800 flex items-center">
                        <span class="w-1 h-4 bg-orange-500 rounded-full mr-2"></span>
                        Metode Pembayaran
                    </h3>

                    <div>
                        
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider ml-1 mb-2">QRIS & E-Wallet</p>
                        
                        <div class="grid grid-cols-3 gap-2.5">
                            <button class="payment-method-btn flex flex-col items-center justify-center py-3 px-2 border border-gray-200 rounded-xl hover:bg-gray-50 hover:border-orange-500 hover:shadow-sm transition-all duration-200 group bg-white" data-method="qris">
                                <div class="h-6 w-full flex items-center justify-center mb-2">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Logo_QRIS.svg" class="max-h-full max-w-[48px] filter grayscale group-hover:grayscale-0 transition-all opacity-70 group-hover:opacity-100 object-contain">
                                </div>
                                <span class="text-[10px] font-semibold text-gray-500 group-hover:text-orange-700 transition-colors">Scan QR</span>
                            </button>
                            <button class="payment-method-btn flex flex-col items-center justify-center py-3 px-2 border border-gray-200 rounded-xl hover:bg-gray-50 hover:border-orange-500 hover:shadow-sm transition-all duration-200 group bg-white" data-method="gopay">
                                <div class="h-6 w-full flex items-center justify-center mb-2">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/0/00/Logo_Gopay.svg" class="max-h-full max-w-[48px] filter grayscale group-hover:grayscale-0 transition-all opacity-70 group-hover:opacity-100 object-contain">
                                </div>
                                <span class="text-[10px] font-semibold text-gray-500 group-hover:text-orange-700 transition-colors">GoPay</span>
                            </button>
                            <button class="payment-method-btn flex flex-col items-center justify-center py-3 px-2 border border-gray-200 rounded-xl hover:bg-gray-50 hover:border-orange-500 hover:shadow-sm transition-all duration-200 group bg-white" data-method="shopeepay">
                                <div class="h-6 w-full flex items-center justify-center mb-2">
                                    <img src="https://images.seeklogo.com/logo-png/40/1/shopee-pay-logo-png_seeklogo-406839.png" class="max-h-full max-w-[44px] filter grayscale group-hover:grayscale-0 transition-all opacity-70 group-hover:opacity-100 object-contain">
                                </div>
                                <span class="text-[10px] font-semibold text-gray-500 group-hover:text-orange-700 transition-colors">ShopeePay</span>
                            </button>
                            <button class="payment-method-btn flex flex-col items-center justify-center py-3 px-2 border border-gray-200 rounded-xl hover:bg-gray-50 hover:border-orange-500 hover:shadow-sm transition-all duration-200 group bg-white" data-method="dana">
                                <div class="h-6 w-full flex items-center justify-center mb-2">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/7/72/Logo_dana_blue.svg" class="max-h-full max-w-[48px] filter grayscale group-hover:grayscale-0 transition-all opacity-70 group-hover:opacity-100 object-contain">
                                </div>
                                <span class="text-[10px] font-semibold text-gray-500 group-hover:text-orange-700 transition-colors">Dana (Link)</span>
                            </button>
                            <button class="payment-method-btn flex flex-col items-center justify-center py-3 px-2 border border-gray-200 rounded-xl hover:bg-gray-50 hover:border-orange-500 hover:shadow-sm transition-all duration-200 group bg-white" data-method="ovo">
                                <div class="h-6 w-full flex items-center justify-center mb-2">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/e/eb/Logo_ovo_purple.svg" class="max-h-full max-w-[36px] filter grayscale group-hover:grayscale-0 transition-all opacity-70 group-hover:opacity-100 object-contain">
                                </div>
                                <span class="text-[10px] font-semibold text-gray-500 group-hover:text-orange-700 transition-colors">OVO (Link)</span>
                            </button>
                            <button class="payment-method-btn flex flex-col items-center justify-center py-3 px-2 border border-gray-200 rounded-xl hover:bg-gray-50 hover:border-orange-500 hover:shadow-sm transition-all duration-200 group bg-white" data-method="linkaja">
                                <div class="h-6 w-full flex items-center justify-center mb-2">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/8/85/LinkAja.svg" class="max-h-full max-w-[32px] filter grayscale group-hover:grayscale-0 transition-all opacity-70 group-hover:opacity-100 object-contain">
                                </div>
                                <span class="text-[10px] font-semibold text-gray-500 group-hover:text-orange-700 transition-colors">LinkAja (Link)</span>
                            </button>
                        </div>
                    </div>

                    
                    <div>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider ml-1 mb-2 mt-4">Virtual Account</p>
                        
                        <div class="grid grid-cols-2 gap-2.5">
                            @foreach(['bca_va' => ['BCA', 'img', 'https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg'], 
                                    'mandiri_va' => ['Mandiri', 'img', 'https://upload.wikimedia.org/wikipedia/commons/a/ad/Bank_Mandiri_logo_2016.svg'],
                                    'bri_va' => ['BRI', 'img', 'https://upload.wikimedia.org/wikipedia/commons/6/68/BANK_BRI_logo.svg'],
                                    'bni_va' => ['BNI', 'img', 'https://upload.wikimedia.org/wikipedia/commons/f/f0/Bank_Negara_Indonesia_logo_%282004%29.svg'],
                                    'permata_va' => ['Permata', 'icon', 'fas fa-university text-emerald-600'],
                                    'cimb_va' => ['CIMB', 'icon', 'fas fa-building text-red-600']
                                    ] as $method => $details)
                            <button class="flex items-center px-4 py-3 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 hover:border-orange-500 hover:shadow-sm payment-method-btn transition-all text-left group" data-method="{{ $method }}">
                                @if($details[1] === 'img')
                                    <div class="h-5 w-8 flex-shrink-0 flex items-center justify-start mr-2 opacity-75 group-hover:opacity-100 transition-all">
                                        <img src="{{ $details[2] }}" class="max-h-full max-w-full object-contain filter grayscale group-hover:grayscale-0">
                                    </div>
                                @else
                                    <div class="h-5 w-8 flex-shrink-0 flex items-center justify-start mr-2 opacity-75 group-hover:opacity-100 transition-all">
                                        <i class="{{ $details[2] }} text-[18px]"></i>
                                    </div>
                                @endif
                                <span class="text-[11px] font-semibold text-gray-600 group-hover:text-gray-900 transition-colors">{{ $details[0] }}</span>
                            </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                
                <div class="flex gap-2.5 pt-5 border-t border-gray-100 mt-6">
                    <button id="leave-page-button"
                        class="w-1/3 bg-white border-2 border-gray-200 text-gray-600 font-bold py-3 rounded-xl hover:text-orange-600 hover:border-orange-200 hover:bg-orange-50 transition text-xs shadow-sm">
                        Bayar Nanti
                    </button>
                    <button id="pay-button"
                        class="w-2/3 bg-orange-600 border-2 border-orange-600 text-white font-bold py-3 px-4 rounded-xl hover:bg-orange-700 hover:border-orange-700 transition shadow-md shadow-orange-200/50 disabled:opacity-50 disabled:cursor-not-allowed transform active:scale-[0.98] flex items-center justify-center text-[13px]"
                        disabled>
                        Pilih Metode
                    </button>
                </div>
                <p class="text-xs text-center text-gray-400 mt-2">
                    Link pembayaran aman tersimpan di WhatsApp Anda.
                </p>
                
                <p class="text-center text-[10px] text-gray-300 mt-6">
                    &copy; <span class="font-semibold text-gray-500">Created By : </span> — Kurniawan Dwi Prasetyo<br>
                    <span class="text-gray-400">Hak Cipta Dilindungi.</span>
                </p>
            </div>
        </div>
    </div>

    
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
                        btn.classList.remove('border-orange-500', 'bg-orange-50', 'ring-2', 'ring-orange-100', 'shadow-md');
                        btn.classList.add('border-gray-200');
                        const img = btn.querySelector('img');
                        if(img) {
                            img.classList.add('grayscale', 'opacity-70');
                            img.classList.remove('grayscale-0', 'opacity-100');
                        }
                    });

                    // Set Active
                    this.classList.remove('border-gray-200');
                    this.classList.add('border-orange-500', 'bg-orange-50', 'ring-2', 'ring-orange-100', 'shadow-md');
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
