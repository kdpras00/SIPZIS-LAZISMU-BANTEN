@extends('layouts.app')

@section('page-title', 'Edit Profil - Lazismu Banten')

@section('content')
<main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8" role="main">
    
    <header class="flex items-center justify-between mb-6 pb-4 border-b border-[#f0ece6]">
        <div class="flex items-center gap-3">
            @php
                $backUrl = request()->route()->hasParameter('muzakki') ? route('muzakki.index') : route('dashboard');
            @endphp
            <a href="{{ $backUrl }}" class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-white border border-[#e8e0d6] text-[#8b7e74] hover:text-[#1c0f0a] hover:bg-[#f0ece6] transition-all shadow-2xs" aria-label="Kembali ke Dashboard">
                <i class="bi bi-arrow-left text-lg"></i>
            </a>
            <div>
                <h1 class="text-xl font-bold text-[#1c0f0a] tracking-tight mb-0.5">
                    {{ request()->route()->hasParameter('muzakki') ? 'Edit Data Muzakki' : 'Edit Profil Saya' }}
                </h1>
                <p class="text-xs text-[#8b7e74] m-0">Perbarui informasi pribadi, alamat, dan dokumen Anda.</p>
            </div>
        </div>
    </header>

    
    <form action="{{ request()->route()->hasParameter('muzakki') ? route('muzakki.update', $muzakki) : route('profiles.update') }}"
          method="POST" 
          id="muzakkiEditForm" 
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        
        @include('muzakki.partials.edit-documents')

        
        @include('muzakki.partials.edit-personal')

        
        @include('muzakki.partials.edit-address')

        
        <footer class="flex items-center justify-end gap-3 pt-2 pb-6 border-t border-[#f0ece6] mt-6">
            <a href="{{ $backUrl }}" 
               class="px-5 py-2.5 rounded-xl border border-[#e8e0d6] bg-white text-xs font-semibold text-[#8b7e74] hover:text-[#1c0f0a] hover:bg-[#faf8f5] transition-all no-underline">
                Batal
            </a>
            <button type="submit" 
                    class="px-6 py-2.5 rounded-xl bg-[#c2410c] hover:bg-[#9a3412] text-xs font-semibold text-white transition-all shadow-2xs hover:shadow-sm">
                Simpan Perubahan
            </button>
        </footer>
    </form>
</main>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('vendor/intl-tel-input/css/intlTelInput.css') }}">
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<style>
    .iti { 
        width: 100%; 
        display: block;
    }

    .iti input#phone {
        font-size: 0.75rem !important;
        font-weight: 500 !important;
        padding-left: 90px !important;
    }

    .iti__selected-dial-code {
        font-size: 0.75rem !important;
        font-weight: 500 !important;
        color: #1c0f0a !important;
    }
    
    .iti__flag-container {
        height: 100% !important;
        top: 0 !important;
        bottom: 0 !important;
        border-top-left-radius: 0.75rem;
        border-bottom-left-radius: 0.75rem;
        background-color: transparent !important;
    }
    
    .iti__selected-flag {
        height: 100% !important;
        display: flex !important;
        align-items: center !important;
        font-size: 0.75rem !important;
        border-top-left-radius: 0.75rem;
        border-bottom-left-radius: 0.75rem;
        background-color: transparent !important;
        padding: 0 12px !important;
    }

    .iti__country-list {
        z-index: 1050 !important;
        border-radius: 0.75rem !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1) !important;
        border: 1px solid #e8e0d6 !important;
        font-size: 0.75rem !important;
    }

    /* Tom Select Custom Styling to Match Lazismu Burnt Clay Theme */
    .ts-wrapper.single .ts-control {
        height: 44px !important;
        padding: 0 16px !important;
        border-radius: 12px !important;
        border: 1px solid #e8e0d6 !important;
        font-size: 0.75rem !important;
        font-weight: 500 !important;
        color: #1c0f0a !important;
        background-color: #ffffff !important;
        display: flex !important;
        align-items: center !important;
        box-shadow: none !important;
    }

    .ts-wrapper.single.focus .ts-control {
        border-color: #c2410c !important;
        box-shadow: 0 0 0 3px rgba(194, 65, 12, 0.1) !important;
    }

    .ts-wrapper.single .ts-control::after {
        border-color: #8b7e74 transparent transparent transparent !important;
        right: 16px !important;
    }

    .ts-wrapper.single.open .ts-control::after {
        border-color: transparent transparent #8b7e74 transparent !important;
    }

    .ts-dropdown {
        border-radius: 12px !important;
        border: 1px solid #e8e0d6 !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05) !important;
        font-size: 0.75rem !important;
        z-index: 1060 !important;
        background-color: #ffffff !important;
        padding: 4px !important;
    }

    .ts-dropdown .option {
        padding: 8px 12px !important;
        border-radius: 8px !important;
        color: #1c0f0a !important;
        cursor: pointer !important;
    }

    .ts-dropdown .active {
        background-color: #fff7ed !important;
        color: #c2410c !important;
    }

    .ts-control input {
        font-size: 0.75rem !important;
    }

    /* Prevent Tom Select search inputs from inheriting standard text input styles (nested box bug) */
    .ts-wrapper .ts-control input[type="text"],
    .ts-wrapper .ts-control input[type="text"]:focus {
        border: none !important;
        background: transparent !important;
        box-shadow: none !important;
        padding: 0 !important;
        margin: 0 !important;
        outline: none !important;
        height: auto !important;
        width: 100% !important;
    }

    /* Hide input cursor for dropdowns */
    .ts-control input,
    .no-search .ts-control input {
        opacity: 0 !important;
        width: 0 !important;
        height: 0 !important;
        position: absolute !important;
        pointer-events: none !important;
        padding: 0 !important;
        margin: 0 !important;
        display: none !important;
    }

    /* Hide the custom chevron-down element next to select when Tom Select is active */
    select.tomselected ~ .pointer-events-none,
    .ts-wrapper + .pointer-events-none {
        display: none !important;
    }

    /* Force hide the original select element to prevent any stray text (-- or option labels) from rendering */
    select.tomselected {
        display: none !important;
        opacity: 0 !important;
        pointer-events: none !important;
        visibility: hidden !important;
    }

    /* Hide Tom Select's built-in clear button (x mark) completely via CSS */
    .ts-wrapper .clear-button,
    .ts-wrapper .clear_button,
    .clear-button,
    .clear_button {
        display: none !important;
        visibility: hidden !important;
        opacity: 0 !important;
        width: 0 !important;
        height: 0 !important;
        pointer-events: none !important;
    }

    /* Style for readonly or disabled inputs to make them obviously greyed-out and unchangeable (increased specificity to override app.blade.php) */
    main input[type="text"][readonly],
    main input[type="email"][readonly],
    main input[type="password"][readonly],
    main input[type="tel"][readonly],
    main input[type="url"][readonly],
    main input[type="number"][readonly],
    main textarea[readonly],
    main input[type="text"][disabled],
    main input[type="email"][disabled],
    main input[type="password"][disabled],
    main input[type="tel"][disabled],
    main input[type="url"][disabled],
    main input[type="number"][disabled],
    main select[disabled],
    main textarea[disabled] {
        background: #f3f4f6 !important;
        background-color: #f3f4f6 !important; /* Clean light gray */
        color: #9ca3af !important; /* Muted gray text */
        border-color: #e5e7eb !important; /* Soft gray border */
        cursor: not-allowed !important; /* Show not-allowed cursor */
        pointer-events: none !important; /* Disable any mouse interaction */
        box-shadow: none !important;
    }

    /* Optional: mute icons inside relative wrappers of disabled inputs */
    main .relative:has(input[readonly]) .pointer-events-none,
    main .relative:has(input[disabled]) .pointer-events-none {
        color: #9ca3af !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script src="{{ asset('vendor/intl-tel-input/js/intlTelInput.min.js') }}"></script>
<script src="{{ asset('js/profile-edit.js') }}"></script>
@endpush