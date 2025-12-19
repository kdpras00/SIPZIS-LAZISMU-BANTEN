@extends('layouts.main')

@section('title', 'Donasi Program - SIPZIS')

{{-- Add CSS untuk intl-tel-input --}}
@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.12/build/css/intlTelInput.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        /* Optimized styles for intl-tel-input */
        .iti {
            width: 100%;
            display: block
        }

        .iti__country-list {
            z-index: 9999
        }

        .iti__tel-input {
            width: 100%;
            border: 2px solid #e5e7eb !important;
            border-radius: .75rem !important;
            padding: .75rem 1rem !important;
            font-size: 1rem;
            line-height: 1.5
        }

        .iti__tel-input:focus {
            border-color: #10b981 !important;
            outline: none !important;
            box-shadow: none !important
        }

        .iti__selected-flag {
            padding: 0 0 0 1rem !important;
            border-radius: .75rem 0 0 .75rem
        }

        .iti__tel-input.border-red-300 {
            border-color: #fca5a5 !important
        }

        .iti__tel-input.border-emerald-300 {
            border-color: #6ee7b7 !important
        }

        .iti--separate-dial-code .iti__tel-input,
        #phone_input.iti__tel-input,
        #phone_input_optional.iti__tel-input {
            padding-left: 90px !important
        }

        .iti--separate-dial-code .iti__selected-dial-code {
            margin-left: 10px !important;
            font-weight: 500;
            color: #374151;
            font-size: 1rem
        }

        .iti--separate-dial-code .iti__selected-flag {
            background-color: transparent !important;
            padding-right: 10px !important
        }

        .iti__flag-container {
            padding-right: 10px !important
        }
    </style>
@endpush

@section('content')
    <div class="min-h-screen bg-gray-50 pb-32 sm:pb-12 font-sans">
        {{-- Top Navigation / Header --}}
        <div class="bg-emerald-600 pb-32 pt-8 px-4 shadow-sm">
            <div class="max-w-2xl mx-auto">
                <a href="{{ route('home') }}" class="inline-flex items-center text-emerald-100 hover:text-white mb-6 transition-colors font-medium">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
                <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight">Mulai Berbagi Kebaikan</h1>
                <p class="text-emerald-100 mt-2 text-sm sm:text-base">Lengkapi formulir di bawah untuk berdonasi.</p>
            </div>
        </div>

        <div class="max-w-2xl mx-auto px-4 -mt-24 relative z-10">
            {{-- Campaign Info Card --}}
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden mb-6 transition-transform hover:scale-[1.01] duration-300">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row gap-5 items-start">
                        @php
                            if (isset($campaign)) {
                                $imageUrl = $campaign->image_url;
                            } elseif (isset($program)) {
                                $imageUrl = $program->image_url;
                            } else {
                                $categoryProgram = \App\Models\Program::byCategory($programCategory)->first();
                                $imageUrl = $categoryProgram ? $categoryProgram->image_url : asset('img/masjid.webp');
                            }
                        @endphp
                        
                        <div class="relative w-full sm:w-auto">
                            <img src="{{ $imageUrl }}" alt="Program {{ $displayTitle }}"
                                class="w-full sm:w-28 sm:h-28 h-48 object-cover rounded-xl shadow-sm">
                            <div class="absolute top-2 right-2 sm:hidden bg-white/90 backdrop-blur-sm px-2 py-1 rounded-lg text-xs font-bold text-emerald-700 shadow-sm border border-emerald-100">
                                <i class="fas fa-check-circle mr-1"></i> Official
                            </div>
                        </div>

                        <div class="flex-1 w-full">
                            <div class="hidden sm:inline-block px-3 py-1 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-full mb-3 border border-emerald-100">
                                <i class="fas fa-check-circle mr-1"></i> Program Resmi Lazismu
                            </div>
                            <h2 class="text-xl font-bold text-gray-900 leading-tight mb-2">
                                {{ $displayTitle }}
                            </h2>
                            <p class="text-sm text-gray-500 leading-relaxed mb-1">{{ $displaySubtitle }}</p>
                        </div>
                    </div>

                    @php
                        $targetAmount = 0;
                        $collectedAmount = 0;
                        
                        if (isset($campaign)) {
                            $targetAmount = $campaign->target_amount;
                            $collectedAmount = $campaign->collected_amount;
                        } elseif (isset($program)) {
                            $targetAmount = $program->target_amount;
                            $collectedAmount = $program->collected_amount;
                        }
                        
                        $percentage = ($targetAmount > 0) ? min(100, round(($collectedAmount / $targetAmount) * 100)) : 0;
                    @endphp

                    @if($targetAmount > 0)
                        <div class="mt-6 pt-5 border-t border-gray-50">
                            <div class="flex justify-between items-end mb-2">
                                <div>
                                    <span class="text-xs text-gray-500 font-medium uppercase tracking-wider">Terkumpul</span>
                                    <div class="text-emerald-700 font-bold text-lg">Rp {{ number_format($collectedAmount, 0, ',', '.') }}</div>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs text-gray-400">dari target</span>
                                    <div class="text-gray-600 font-semibold text-sm">Rp {{ number_format($targetAmount, 0, ',', '.') }}</div>
                                </div>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                                <div class="bg-gradient-to-r from-emerald-500 to-teal-400 h-3 rounded-full transition-all duration-1000 ease-out" 
                                     style="width: 0%" onload="this.style.width='{{ $percentage }}%'"></div>
                                     <script>setTimeout(() => document.querySelector('.bg-gradient-to-r').style.width = '{{ $percentage }}%', 100);</script>
                            </div>
                            <div class="text-right mt-1">
                                <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md">{{ $percentage }}% Tercapai</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Donation Form Card --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 sm:p-8 relative">
                <form id="donation-form" class="space-y-8" autocomplete="off">
                    @csrf
                    
                    {{-- Hidden inputs --}}
                    <input type="hidden" name="program_category" value="{{ $programCategory }}" autocomplete="off">
                    @if (isset($program))
                        <input type="hidden" name="program_id" value="{{ $program->id }}" autocomplete="off">
                    @endif
                    <input type="hidden" name="zakat_type_id"
                        value="{{ request()->query('type') === 'profesi' ? 3 : (request()->query('type') === 'harta' ? 1 : (request()->query('type') === 'mal' ? 2 : '')) }}"
                        autocomplete="off">
                    <input type="hidden" name="program_type_id" id="program_type_id"
                        value="{{ request()->query('program_type_id') }}" autocomplete="off">
                    <input type="hidden" name="zakat_amount" id="zakat_amount" value="0" autocomplete="off">
                    <input type="hidden" name="paid_amount" id="paid_amount" value="0" autocomplete="off">
                    <input type="hidden" name="donor_phone" id="donor_phone_full" autocomplete="off">
                    <input type="hidden" name="payment_method" value="" autocomplete="off">

                    {{-- Section: Nominal Donasi --}}
                    <div>
                        <div class="flex items-center mb-4">
                            <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 mr-3 font-bold text-sm">1</div>
                            <h3 class="text-gray-800 font-bold text-lg">Pilih Nominal Donasi</h3>
                        </div>
                        
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-4">
                            @foreach([10000, 20000, 50000, 100000] as $amt)
                            <button type="button"
                                class="quick-amount-btn group relative flex flex-col items-center justify-center p-4 border-2 border-gray-100 rounded-xl hover:border-emerald-500 hover:bg-emerald-50 transition-all duration-200"
                                data-amount="{{ $amt }}">
                                <span class="text-emerald-600 font-bold text-lg group-[.selected]:scale-110 transition-transform">Rp {{ number_format($amt / 1000, 0) }}k</span>
                                <span class="text-xs text-gray-400 mt-1 font-medium group-hover:text-emerald-600">Rp {{ number_format($amt, 0, ',', '.') }}</span>
                                {{-- Checkmark Icon for Active State --}}
                                <div class="absolute top-2 right-2 w-5 h-5 bg-emerald-500 rounded-full text-white text-xs flex items-center justify-center opacity-0 scale-0 transition-all duration-200 check-icon">
                                    <i class="fas fa-check"></i>
                                </div>
                            </button>
                            @endforeach
                            
                            {{-- Custom Amount Button --}}
                            <button type="button"
                                class="quick-amount-btn col-span-2 sm:col-span-1 group relative flex flex-col items-center justify-center p-4 border-2 border-gray-100 rounded-xl hover:border-emerald-500 hover:bg-emerald-50 transition-all duration-200"
                                data-amount="custom">
                                <span class="text-gray-600 font-bold text-lg group-hover:text-emerald-600">Nominal Lain</span>
                                <span class="text-xs text-gray-400 mt-1 font-medium">Isi Manual</span>
                                <div class="absolute top-2 right-2 w-5 h-5 bg-emerald-500 rounded-full text-white text-xs flex items-center justify-center opacity-0 scale-0 transition-all duration-200 check-icon">
                                    <i class="fas fa-check"></i>
                                </div>
                            </button>
                        </div>

                        {{-- Custom Input (Initially Hidden or Subtle) --}}
                        <div class="relative transition-all duration-300 transform origin-top" id="custom-amount-container">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 font-bold">Rp</span>
                            <input type="text" id="donation_amount_display" inputmode="numeric"
                                oninput="formatAndSetValues(this)"
                                class="w-full border-2 border-gray-200 rounded-xl pl-12 pr-4 py-4 font-bold text-lg text-gray-800 placeholder-gray-300 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-50 focus:outline-none transition-all"
                                placeholder="Masukkan nominal donasi (Min. Rp 10.000)" required autocomplete="off">
                        </div>
                    </div>

                    {{-- Section: Data Donatur --}}
                    <div class="pt-2">
                        <div class="flex items-center mb-4">
                            <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 mr-3 font-bold text-sm">2</div>
                            <h3 class="text-gray-800 font-bold text-lg">Data Hamba Allah</h3>
                        </div>

                        {{-- Donor Information Fields --}}
                        <div class="space-y-4">
                            @if (!isset($loggedInMuzakki))
                                {{-- Guest User Form Fields --}}
                                <div>
                                    <label for="donor_name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                                    <input type="text" id="donor_name" name="donor_name"
                                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-emerald-500 focus:outline-none transition-colors"
                                        placeholder="Nama Lengkap" required autocomplete="off">
                                </div>
    
                                <div>
                                    <label for="phone_input" class="block text-sm font-semibold text-gray-700 mb-2">Nomor WhatsApp <span class="text-red-500">*</span></label>
                                    <input type="tel" id="phone_input" placeholder="81234567890" required autocomplete="off" class="w-full">
                                    <p class="text-xs text-gray-500 mt-1" id="phone_error"></p>
                                </div>
    
                                <div>
                                    <label for="donor_email" class="block text-sm font-semibold text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                                    <input type="email" id="donor_email" name="donor_email"
                                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-emerald-500 focus:outline-none transition-colors"
                                        placeholder="email@contoh.com" required autocomplete="off">
                                </div>
                            @else
                                {{-- Logged in Users --}}
                                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 font-bold text-lg cursor-default">
                                        {{ substr($loggedInMuzakki->name, 0, 1) }}
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-500">Masuk sebagai:</p>
                                        <p class="font-bold text-gray-800">{{ $loggedInMuzakki->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $loggedInMuzakki->email }}</p>
                                    </div>
                                </div>

                                {{-- Optional Phone logic for logged in users --}}
                                @if (!$loggedInMuzakki->phone)
                                    <div class="mt-4">
                                        <label for="phone_input_optional" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Nomor WhatsApp <span class="text-gray-500 text-xs font-normal">(Kami sarankan diisi)</span>
                                        </label>
                                        <input type="tel" id="phone_input_optional" placeholder="81234567890" autocomplete="off">
                                    </div>
                                    <input type="hidden" name="donor_name" value="{{ $loggedInMuzakki->name }}" autocomplete="off">
                                    <input type="hidden" name="donor_email" value="{{ $loggedInMuzakki->email }}" autocomplete="off">
                                @else
                                    <input type="hidden" name="donor_name" value="{{ $loggedInMuzakki->name }}" autocomplete="off">
                                    <input type="hidden" id="donor_phone_hidden" value="{{ $loggedInMuzakki->phone }}" autocomplete="off">
                                    <input type="hidden" name="donor_email" value="{{ $loggedInMuzakki->email }}" autocomplete="off">
                                @endif
                            @endif

                            {{-- Message/Doa --}}
                            <div class="pt-2">
                                <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">Pesan / Doa <span class="font-normal text-gray-400">(Opsional)</span></label>
                                <textarea name="notes" rows="3"
                                    class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-emerald-500 focus:outline-none transition-colors"
                                    placeholder="Tulis doa untuk Anda dan Keluarga..." autocomplete="off"></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Desktop Submit Button --}}
                    <div class="pt-6 border-t border-gray-100 hidden sm:block">
                        <button type="submit"
                            class="w-full bg-yellow-500 text-white px-8 py-4 rounded-xl hover:bg-yellow-600 font-bold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all flex items-center justify-center group">
                            <span>Lanjut Pembayaran</span>
                            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                        </button>
                        
                        {{-- Trust Signals --}}
                        <div class="flex items-center justify-center gap-6 mt-6">
                             <div class="flex items-center gap-2 text-gray-400">
                                <i class="fas fa-lock text-emerald-500"></i>
                                <span class="text-xs font-medium">Pembayaran Aman</span>
                            </div>
                            <div class="flex items-center gap-2 text-gray-400">
                                <i class="fas fa-check-circle text-blue-500"></i>
                                <span class="text-xs font-medium">Terverifikasi</span>
                            </div>
                        </div>
                    </div>
                        {{-- Doa Ticker (Social Proof) --}}
                        <div class="mt-8 bg-gray-50 rounded-xl p-5 border border-gray-100">
                             <div class="flex items-center gap-2 mb-3">
                                <i class="fas fa-praying-hands text-emerald-500"></i>
                                <h4 class="font-bold text-gray-700 text-sm">Doa-doa Orang Baik</h4>
                            </div>
                            <div class="relative h-32 overflow-hidden mx-auto">
                                <div class="absolute inset-x-0 top-0 h-8 bg-gradient-to-b from-gray-50 to-transparent z-10"></div>
                                <div class="absolute inset-x-0 bottom-0 h-8 bg-gradient-to-t from-gray-50 to-transparent z-10"></div>
                                
                                <div class="animate-marquee-vertical space-y-3">
                                    {{-- Mock Data - In real app, loop from DB --}}
                                    @foreach([
                                        ["Hamba Allah", "Semoga berkah untuk semua penerima."],
                                        ["Rizky", "Bismillah, semoga dilancarkan rezekinya."],
                                        ["Aisyah", "Ya Allah, sehatkanlah orang tua saya."],
                                        ["Abdullah", "Semoga menjadi amal jariyah."],
                                        ["Hamba Allah", "Untuk kesembuhan keluarga."],
                                        ["Siti", "Semoga bermanfaat bagi umat."]
                                    ] as $doa)
                                    <div class="text-sm bg-white p-2 rounded-lg shadow-sm border border-gray-100">
                                        <span class="font-bold text-gray-800 mr-1">{{ $doa[0] }}:</span>
                                        <span class="text-gray-600 italic">"{{ $doa[1] }}"</span>
                                    </div>
                                    @endforeach
                                    {{-- Duplicate for smooth loop --}}
                                    @foreach([
                                        ["Hamba Allah", "Semoga berkah untuk semua penerima."],
                                        ["Rizky", "Bismillah, semoga dilancarkan rezekinya."],
                                        ["Aisyah", "Ya Allah, sehatkanlah orang tua saya."],
                                        ["Abdullah", "Semoga menjadi amal jariyah."],
                                        ["Hamba Allah", "Untuk kesembuhan keluarga."],
                                        ["Siti", "Semoga bermanfaat bagi umat."]
                                    ] as $doa)
                                    <div class="text-sm bg-white p-2 rounded-lg shadow-sm border border-gray-100">
                                        <span class="font-bold text-gray-800 mr-1">{{ $doa[0] }}:</span>
                                        <span class="text-gray-600 italic">"{{ $doa[1] }}"</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div> {{-- End Card --}}
        </div> {{-- End Container --}}
    </div>
    
    <style>
    @keyframes marquee-vertical {
        0% { transform: translateY(0); }
        100% { transform: translateY(-50%); }
    }
    .animate-marquee-vertical {
        animation: marquee-vertical 20s linear infinite;
    }
    .animate-marquee-vertical:hover {
        animation-play-state: paused;
    }
    </style>

    {{-- Mobile Sticky Bottom Bar --}}
    <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)] sm:hidden z-50">
        <div class="flex items-center gap-3">
             <div class="flex-1">
                 <p class="text-xs text-gray-500 mb-0.5">Total Donasi</p>
                 <p class="font-bold text-emerald-600 text-lg leading-none" id="mobile-amount-display">Rp 0</p>
             </div>
             <button type="button" onclick="document.querySelector('#donation-form').requestSubmit()"
                class="bg-yellow-500 text-white px-6 py-3 rounded-xl font-bold text-sm shadow-md hover:bg-yellow-600 active:scale-95 transition-all">
                Lanjut Bayar
            </button>
        </div>
    </div>
@endsection

{{-- Add JS untuk intl-tel-input --}}
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.12/build/js/intlTelInput.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let iti, itiOptional;
        const itiConfig = {
            initialCountry: "id",
            preferredCountries: ["id", "my", "sg"],
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.12/build/js/utils.js",
            separateDialCode: true,
            autoPlaceholder: "aggressive",
            formatOnDisplay: true,
            nationalMode: false
        };

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize intl-tel-input
            const phoneInput = document.querySelector("#phone_input");
            if (phoneInput) {
                iti = window.intlTelInput(phoneInput, {
                    ...itiConfig,
                    customPlaceholder: (p) => p
                });
                phoneInput.addEventListener('blur', () => validatePhone(iti, phoneInput));
                phoneInput.addEventListener('input', () => {
                    document.getElementById('phone_error').textContent = '';
                    phoneInput.classList.remove('border-red-300', 'border-emerald-300');
                });

                // Prevent national format (0 prefix) for Indonesia only
                let isUpdating = false;
                phoneInput.addEventListener('input', function() {
                    if (isUpdating) return;

                    const selectedCountry = iti.getSelectedCountryData();
                    if (selectedCountry.iso2 === 'id') {
                        const currentNumber = iti.getNumber();
                        // Check if it has leading 0 after +62 (national format)
                        if (currentNumber.match(/^\+620/)) {
                            isUpdating = true;
                            const cleanNumber = currentNumber.replace(/^\+620/, '+62').replace(/\D/g, '')
                                .replace(/^62/, '');
                            if (cleanNumber) {
                                iti.setNumber('+62' + cleanNumber);
                            }
                            setTimeout(() => {
                                isUpdating = false;
                            }, 100);
                        }
                    }
                });

                // Prevent national format when country changes
                phoneInput.addEventListener('countrychange', function() {
                    const selectedCountry = iti.getSelectedCountryData();
                    if (selectedCountry.iso2 === 'id') {
                        const currentNumber = iti.getNumber();
                        const cleanNumber = currentNumber.replace(/^\+62/, '').replace(/^0+/, '');
                        if (cleanNumber && cleanNumber !== currentNumber.replace(/^\+62/, '')) {
                            iti.setNumber('+62' + cleanNumber);
                        }
                    }
                });
            }

            const phoneInputOptional = document.querySelector("#phone_input_optional");
            if (phoneInputOptional) {
                itiOptional = window.intlTelInput(phoneInputOptional, itiConfig);
                phoneInputOptional.addEventListener('blur', () => {
                    if (phoneInputOptional.value.trim()) validatePhone(itiOptional, phoneInputOptional);
                });

                // Prevent national format (0 prefix) for Indonesia only
                let isUpdatingOptional = false;
                phoneInputOptional.addEventListener('input', function() {
                    if (isUpdatingOptional) return;

                    const selectedCountry = itiOptional.getSelectedCountryData();
                    if (selectedCountry.iso2 === 'id') {
                        const currentNumber = itiOptional.getNumber();
                        if (currentNumber.match(/^\+620/)) {
                            isUpdatingOptional = true;
                            const cleanNumber = currentNumber.replace(/^\+620/, '+62').replace(/\D/g, '')
                                .replace(/^62/, '');
                            if (cleanNumber) {
                                itiOptional.setNumber('+62' + cleanNumber);
                            }
                            setTimeout(() => {
                                isUpdatingOptional = false;
                            }, 100);
                        }
                    }
                });

                phoneInputOptional.addEventListener('countrychange', function() {
                    const selectedCountry = itiOptional.getSelectedCountryData();
                    if (selectedCountry.iso2 === 'id') {
                        const currentNumber = itiOptional.getNumber();
                        const cleanNumber = currentNumber.replace(/^\+62/, '').replace(/^0+/, '');
                        if (cleanNumber && cleanNumber !== currentNumber.replace(/^\+62/, '')) {
                            itiOptional.setNumber('+62' + cleanNumber);
                        }
                    }
                });
            }

            // Handle URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            const amount = urlParams.get('amount');
            if (amount) {
                document.getElementById('donation_amount_display').value = new Intl.NumberFormat('id-ID').format(
                    amount);
                document.getElementById('paid_amount').value = amount;
                const btn = document.querySelector(`.quick-amount-btn[data-amount="${amount}"]`);
                if (btn) btn.classList.add('selected');
            }

            const programTypeId = urlParams.get('program_type_id');
            if (programTypeId) document.getElementById('program_type_id').value = programTypeId;

            const programCategory = urlParams.get('category');
            if (programCategory) document.querySelector('input[name="program_category"]').value = programCategory;

            const existingPhone = document.getElementById('donor_phone_hidden');
            if (existingPhone?.value) document.getElementById('donor_phone_full').value = existingPhone.value;
        });

        function validatePhone(itiInstance, inputElement) {
            if (!inputElement.value.trim()) return true;
            const isValid = itiInstance.isValidNumber();
            const errorEl = inputElement.id === 'phone_input' ? document.getElementById('phone_error') : null;

            inputElement.classList.toggle('border-red-300', !isValid);
            inputElement.classList.toggle('border-emerald-300', isValid);

            if (errorEl) {
                if (isValid) {
                    errorEl.textContent = '';
                } else {
                    const errorMap = ["Invalid number", "Invalid country code", "Too short", "Too long", "Invalid number"];
                    errorEl.textContent = errorMap[itiInstance.getValidationError()] || "Nomor tidak valid";
                    errorEl.classList.add('text-red-500');
                }
            }
            return isValid;
        }

        function formatAndSetValues(el) {
            const raw = el.value.replace(/\D/g, '');
            const numericValue = parseInt(raw) || 0;

            document.getElementById('paid_amount').value = numericValue;
            
            // Update Mobile Display
            document.getElementById('mobile-amount-display').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(numericValue);

            if (raw) {
                el.value = new Intl.NumberFormat('id-ID').format(raw);
                // Helper to remove selected state from all
                document.querySelectorAll('.quick-amount-btn').forEach(b => {
                    b.classList.remove('border-emerald-500', 'bg-emerald-50', 'selected');
                    b.classList.add('border-gray-100');
                    const icon = b.querySelector('.check-icon');
                    if(icon) {
                        icon.classList.remove('opacity-100', 'scale-100');
                        icon.classList.add('opacity-0', 'scale-0');
                    }
                });
            } else {
                el.value = '';
            }
        }

        document.querySelectorAll('.quick-amount-btn').forEach(button => {
            button.addEventListener('click', function() {
                const amount = this.dataset.amount;
                
                // Reset all buttons
                document.querySelectorAll('.quick-amount-btn').forEach(b => {
                    b.classList.remove('border-emerald-500', 'bg-emerald-50', 'selected');
                    b.classList.add('border-gray-100');
                    const icon = b.querySelector('.check-icon');
                    if(icon) {
                        icon.classList.remove('opacity-100', 'scale-100');
                        icon.classList.add('opacity-0', 'scale-0');
                    }
                });

                // Set Active State
                this.classList.remove('border-gray-100');
                this.classList.add('border-emerald-500', 'bg-emerald-50', 'selected');
                const icon = this.querySelector('.check-icon');
                if(icon) {
                    icon.classList.remove('opacity-0', 'scale-0');
                    icon.classList.add('opacity-100', 'scale-100');
                }

                if (amount === 'custom') {
                    document.getElementById('donation_amount_display').focus();
                    // Don't clear value if it's already there
                    return;
                }
                
                document.getElementById('donation_amount_display').value = new Intl.NumberFormat('id-ID').format(amount);
                document.getElementById('paid_amount').value = amount;
                
                // Update Mobile Display
                document.getElementById('mobile-amount-display').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
            });
        });

        document.getElementById('donation-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;

            // Validate phones
            const phoneInput = document.querySelector("#phone_input");
            if (phoneInput && iti) {
                if (!validatePhone(iti, phoneInput)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Nomor Telepon Tidak Valid',
                        text: 'Mohon periksa kembali nomor telepon Anda',
                        confirmButtonColor: '#ef4444'
                    });
                    return;
                }
                // Get full number in international format
                let fullNumber = iti.getNumber();
                // Special handling for Indonesia: remove leading 0 if present
                const selectedCountry = iti.getSelectedCountryData();
                if (selectedCountry.iso2 === 'id') {
                    fullNumber = fullNumber.replace(/^\+620/, '+62');
                }
                document.getElementById('donor_phone_full').value = fullNumber;
            }

            const phoneInputOptional = document.querySelector("#phone_input_optional");
            if (phoneInputOptional?.value.trim() && itiOptional) {
                if (!validatePhone(itiOptional, phoneInputOptional)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Nomor Telepon Tidak Valid',
                        text: 'Mohon periksa kembali nomor telepon Anda',
                        confirmButtonColor: '#ef4444'
                    });
                    return;
                }
                // Get full number in international format
                let fullNumber = itiOptional.getNumber();
                // Special handling for Indonesia: remove leading 0 if present
                const selectedCountry = itiOptional.getSelectedCountryData();
                if (selectedCountry.iso2 === 'id') {
                    fullNumber = fullNumber.replace(/^\+620/, '+62');
                }
                document.getElementById('donor_phone_full').value = fullNumber;
            }

            submitButton.disabled = true;
            submitButton.innerHTML = 'Memproses...';

            try {
                const response = await fetch('{{ route('guest.payment.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': new FormData(this).get('_token'),
                        'Accept': 'application/json',
                    },
                    body: new FormData(this)
                });

                const data = await response.json();

                if (response.ok && data.success && data.redirect_url) {
                    window.location.href = data.redirect_url;
                } else {
                    // Handle validation errors from controller
                    let errorMessage = data.message || 'Silakan cek kembali data Anda.';
                    let errorTitle = 'Terjadi Kesalahan';

                    // If there are validation errors, show them
                    if (data.errors) {
                        const errorMessages = [];
                        for (const field in data.errors) {
                            errorMessages.push(data.errors[field][0]);
                        }
                        errorMessage = errorMessages.join('<br>');
                        errorTitle = 'Validasi Gagal';
                    }

                    Swal.fire({
                        icon: 'error',
                        title: errorTitle,
                        html: errorMessage,
                        confirmButtonColor: '#ef4444'
                    });
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalText;
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Koneksi Error',
                    text: 'Tidak dapat terhubung ke server. Silakan coba lagi.',
                    confirmButtonColor: '#ef4444'
                });
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            }
        });
    </script>
@endpush
