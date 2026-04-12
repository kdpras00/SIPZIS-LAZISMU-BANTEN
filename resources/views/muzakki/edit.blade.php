@extends('layouts.app')

@section('page-title', 'Edit Muzakki - ' . $muzakki->name)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center">
            <a href="{{ request()->route()->hasParameter('muzakki') ? route('muzakki.index') : route('dashboard') }}"
                class="text-gray-500 hover:text-gray-700 mr-4 transition-colors">
                <i class="bi bi-arrow-left text-xl"></i>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">{{ request()->route()->hasParameter('muzakki') ? 'Edit Muzakki' : 'Profil' }}</h1>
        </div>
        <button type="submit" form="muzakkiEditForm"
            class="text-orange-600 hover:text-orange-700 font-semibold text-lg transition-colors">
            Selesai
        </button>
    </div>



    <form
        action="{{ request()->route()->hasParameter('muzakki') ? route('muzakki.update', $muzakki) : route('profile.update') }}"
        method="POST" id="muzakkiEditForm" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Documents Section -->
        @include('muzakki.partials.edit-documents')

        <!-- Personal Information Section -->
        @include('muzakki.partials.edit-personal')

        <!-- Address Section -->
        @include('muzakki.partials.edit-address')

        <!-- Status Section (only for admin) -->
        @if (request()->route()->hasParameter('muzakki') && auth()->user()->role === 'admin')
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Akun</h3>
            <div class="flex items-center">
                <input type="checkbox" id="is_active" name="is_active" value="1" 
                    class="w-5 h-5 text-orange-600 border border-gray-300 rounded focus:ring-orange-500"
                    {{ old('is_active', $muzakki->is_active) ? 'checked' : '' }}>
                <label class="ml-3 text-sm font-medium text-gray-700" for="is_active">
                    Aktifkan akun muzakki
                </label>
            </div>
        </div>
        @endif

        <!-- Phone Verification Status -->
        @if ($muzakki->phone_verified)
        <input type="hidden" name="phone_verified" value="1" id="phone_verified_input">
        @endif
    </form>
</div>

<!-- OTP Verification Modal -->
<!-- Keeping Bootstrap modal classes for JS compatibility, but styling with Tailwind -->
<div class="modal fade" id="otpModal" tabindex="-1" aria-labelledby="otpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-white rounded-2xl shadow-xl border-0 overflow-hidden">
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900" id="otpModalLabel">Verifikasi Nomor Telepon</h3>
                <button type="button" class="text-gray-400 hover:text-gray-500 focus:outline-none" data-bs-dismiss="modal" aria-label="Close">
                    <i class="bi bi-x-lg text-xl"></i>
                </button>
            </div>
            <div class="p-6">
                <p class="text-gray-500 mb-6 text-center">
                    Masukkan kode OTP yang dikirim ke nomor WhatsApp <br>
                    <strong id="displayPhone" class="text-gray-900"></strong>
                </p>

                <div class="flex justify-center gap-3 mb-6">
                    <input type="text" class="w-14 h-14 text-center text-2xl font-bold border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-0 outline-none transition-all otp-input" maxlength="1" id="otp1">
                    <input type="text" class="w-14 h-14 text-center text-2xl font-bold border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-0 outline-none transition-all otp-input" maxlength="1" id="otp2">
                    <input type="text" class="w-14 h-14 text-center text-2xl font-bold border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-0 outline-none transition-all otp-input" maxlength="1" id="otp3">
                    <input type="text" class="w-14 h-14 text-center text-2xl font-bold border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-0 outline-none transition-all otp-input" maxlength="1" id="otp4">
                </div>

                <div class="text-center mb-6">
                    <p class="text-sm text-gray-500">Belum menerima kode?
                        <a href="#" class="text-green-600 hover:text-green-700 font-medium ml-1" id="resendOtp">
                            Kirim kode OTP (<span id="countdown">57</span> detik)
                        </a>
                    </p>
                </div>

                <button type="button" class="w-full py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-xl transition-colors disabled:opacity-50 disabled:cursor-not-allowed" id="verifyOtpBtn" disabled>
                    Verifikasi
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/css/intlTelInput.css">
<style>
    /* Custom styles that are hard to replicate with just Tailwind utilities or for 3rd party libs */
    .iti { 
        width: 100%; 
        display: block;
    }
    
    /* Fix height and border radius to match Tailwind inputs */
    .iti__flag-container {
        height: 100% !important;
        top: 0 !important;
        bottom: 0 !important;
        border-top-left-radius: 0.5rem; /* rounded-lg */
        border-bottom-left-radius: 0.5rem;
        background-color: transparent !important;
    }
    
    .iti__selected-flag {
        height: 100% !important;
        border-top-left-radius: 0.5rem;
        border-bottom-left-radius: 0.5rem;
        background-color: transparent !important; /* Ensure it blends with input bg */
        padding: 0 12px !important; /* Adjust padding */
    }
    
    .iti__selected-flag:hover {
        background-color: rgba(0, 0, 0, 0.05) !important; /* Subtle hover effect */
    }
    
    /* Style the dropdown to match the theme */
    .iti__country-list {
        border-radius: 0.5rem;
        border: 1px solid #e5e7eb; /* border-gray-200 */
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); /* shadow-md */
        margin-top: 4px;
        z-index: 50; /* Ensure it's above other elements */
    }
    
    /* Smooth transitions */
    .otp-input:focus {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.15);
    }
    
    /* Toast Notification Styles - keeping these as they are dynamically added */
    .otp-toast {
        position: fixed;
        top: 20px;
        right: 20px;
        background: white;
        padding: 12px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 9999;
        transform: translateX(400px);
        transition: transform 0.3s ease;
    }
    .otp-toast.show { transform: translateX(0); }
    .otp-toast-success { border-left: 4px solid #16a34a; }
    .otp-toast-warning { border-left: 4px solid #ca8a04; }
    .otp-toast-info { border-left: 4px solid #2563eb; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/intlTelInput.min.js"></script>
<script>
    window.profileConfig = {
        profileCompleteness: {{ $muzakki->profile_completeness }},
        ktpPhotoUrl: {!! json_encode($muzakki->ktp_photo ? asset('storage/' . $muzakki->ktp_photo) : '') !!},
        savedCountry: {!! json_encode($muzakki->country ?? '') !!},
        savedProvince: {!! json_encode($muzakki->province ?? '') !!},
        savedCity: {!! json_encode($muzakki->city ?? '') !!},
        savedDistrict: {!! json_encode($muzakki->district ?? '') !!},
        savedVillage: {!! json_encode($muzakki->village ?? '') !!},
        csrfToken: "{{ csrf_token() }}",
        isPhoneVerified: {{ $muzakki->phone_verified ? 'true' : 'false' }}
    };
</script>
<script src="{{ asset('js/profile-edit.js') }}"></script>
@endpush