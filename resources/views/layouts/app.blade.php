<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ $meta_description ?? 'Lazismu Banten - Portal resmi untuk mengelola dan menyalurkan Zakat, Infaq, dan Sedekah.' }}">
    <meta name="theme-color" content="#c2410c">
    
    
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ isset($title) ? $title . ' - Lazismu Banten' : 'Lazismu Banten' }}">
    <meta property="og:description" content="{{ $meta_description ?? 'Lazismu Banten - Portal resmi pengelolaan ZIS.' }}">
    <meta property="og:image" content="{{ asset('img/logo.png') }}">

    
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ isset($title) ? $title . ' - Lazismu Banten' : 'Lazismu Banten' }}">
    <meta property="twitter:description" content="{{ $meta_description ?? 'Lazismu Banten - Portal resmi pengelolaan ZIS.' }}">
    <meta property="twitter:image" content="{{ asset('img/logo.png') }}">

    <link rel="icon" type="image/x-icon" href="{{ asset('img/lazismu-icon.ico') }}">
    <title>{{ isset($title) ? $title . ' - Lazismu Banten' : 'Lazismu Banten' }}</title>

    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    @stack('styles')

    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        a,
        button {
            transition: color 0.2s ease, background-color 0.2s ease, border-color 0.2s ease, opacity 0.2s ease, box-shadow 0.2s ease;
        }
        input,
        select,
        textarea {
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        /* Remove underline from all links by default */
        a {
            text-decoration: none !important;
        }

        a:hover,
        a:focus {
            text-decoration: none !important;
        }

        /* Body styling */
        body {
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
            /* Mencegah horizontal scroll */
        }

        /* Container styling untuk memastikan layout tidak melebihi viewport */
        .container-fluid {
            max-width: 100%;
            padding-left: 0;
            padding-right: 0;
        }

        /* Main content styling */
        main {
            width: 100%;
            max-width: 100%;
            transition: margin 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            will-change: margin;
        }

        /* Untuk muzakki layout */
        .muzakki-layout {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        /* Responsive adjustments for main content */
        @media (max-width: 767.98px) {
            .p-4 {
                padding: 1rem !important;
            }

            /* Don't hide the aside on mobile, let the sidebar handle visibility */
            aside.col-md-3,
            aside.col-lg-2 {
                position: static;
                display: block !important;
            }

            main.col-md-9,
            main.col-lg-10 {
                width: 100%;
                max-width: 100%;
                margin-left: 0 !important;
                transition: margin 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                will-change: margin;
            }
        }

        /* Desktop sidebar collapsed behavior */
        @media (min-width: 768px) {
            main.sidebar-collapsed {
                margin-left: 0 !important;
                transition: margin 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                will-change: margin;
            }

            /* Ensure smooth transition for main content */
            main.col-md-9,
            main.col-lg-10 {
                transition: margin 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                will-change: margin;
            }

            aside.sidebar-collapsed {
                width: 0 !important;
                overflow: hidden !important;
                transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
                will-change: width;
            }

            main.sidebar-collapsed {
                margin-left: 0 !important;
                width: 100% !important;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
                will-change: margin, width;
            }
        }

        /* Mobile overlay behavior */
        #sidebar-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        /* Ensure sidebar overlay doesn't block navbar */
        #sidebar-overlay {
            z-index: 1040 !important;
        }

        /* Ensure navbar is always above sidebar overlay */
        .navbar {
            z-index: 1051 !important;
        }

        /* Ensure no element blocks navbar clicks */
        main {
            position: relative;
            z-index: 1;
        }

        /* Make sure container-fluid doesn't block */
        .navbar .container-fluid {
            position: relative;
            z-index: 1;
        }

        /* Force hide modal backdrops on page load - AGGRESSIVE */
        .modal-backdrop {
            display: none !important;
            opacity: 0 !important;
            visibility: hidden !important;
            pointer-events: none !important;
        }

        /* Only show backdrop when modal is actually being shown AND user clicked */
        body.modal-open .modal.show ~ .modal-backdrop.show,
        body.modal-open .modal.show + .modal-backdrop.show {
            display: block !important;
            opacity: 0.5 !important;
            visibility: visible !important;
        }

        /* Force remove modal-open if no modal is showing */
        body.modal-open {
            overflow: auto !important;
            padding-right: 0 !important;
        }

        /* ============================================
           SIPZIS GLOBAL THEME — Burnt Clay Humanized
           ============================================ */

        /* --- Color Tokens --- */
        :root {
            --sz-bg: #faf8f5;
            --sz-card: #ffffff;
            --sz-border: #f0ece6;
            --sz-border-strong: #e8e0d6;
            --sz-fg: #1c0f0a;
            --sz-muted: #8b7e74;
            --sz-accent: #c2410c;
            --sz-accent-light: #fff7ed;
            --sz-accent-hover: #9a3412;
            --sz-success: #15803d;
            --sz-info: #0369a1;
            --sz-warning: #b45309;
            --sz-danger: #dc2626;
        }

        /* --- Page Background --- */
        main, main > div { background: var(--sz-bg) !important; }

        /* --- Cards: warm borders, softer shadow --- */
        main .bg-white.rounded-lg,
        main .bg-white.rounded-xl,
        main .bg-white.rounded-2xl {
            border-color: var(--sz-border) !important;
            box-shadow: 0 1px 3px rgba(28,15,10,0.04) !important;
        }

        /* --- Table Header: warm cream --- */
        main thead, main thead th,
        main .bg-gray-50 th {
            background: var(--sz-bg) !important;
            color: var(--sz-muted) !important;
            border-color: var(--sz-border) !important;
            font-size: 0.75rem;
            letter-spacing: 0.04em;
        }

        /* --- Table body rows --- */
        main tbody tr {
            border-color: var(--sz-border) !important;
            transition: background 0.15s ease;
        }
        main tbody tr:hover {
            background: var(--sz-accent-light) !important;
        }
        main tbody td {
            border-color: var(--sz-border) !important;
            color: var(--sz-fg);
        }

        /* --- Section borders --- */
        main .border-gray-200,
        main .border-gray-300,
        main .border-b {
            border-color: var(--sz-border) !important;
        }

        /* --- Form inputs --- */
        main input[type="text"],
        main input[type="email"],
        main input[type="password"],
        main input[type="number"],
        main input[type="date"],
        main input[type="search"],
        main input[type="tel"],
        main input[type="url"],
        main select,
        main textarea {
            border-color: var(--sz-border-strong) !important;
            background: var(--sz-card) !important;
            color: var(--sz-fg) !important;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        main input:focus,
        main select:focus,
        main textarea:focus {
            border-color: var(--sz-accent) !important;
            box-shadow: 0 0 0 3px rgba(194,65,12,0.1) !important;
            outline: none !important;
        }

        /* --- Date Input Custom Calendar Icon & Click-to-Open --- */
        input[type="date"] {
            position: relative;
            cursor: pointer;
        }
        input[type="date"]::-webkit-calendar-picker-indicator {
            cursor: pointer;
            filter: invert(34%) sepia(82%) saturate(2284%) hue-rotate(349deg) brightness(92%) contrast(92%);
            opacity: 0.8;
            transition: opacity 0.2s, transform 0.2s;
            padding: 2px;
        }
        input[type="date"]::-webkit-calendar-picker-indicator:hover {
            opacity: 1;
            transform: scale(1.15);
        }

        /* --- Transparent & Minimalist Sleek Scrollbars for Tables & Containers --- */
        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(139, 126, 116, 0.15);
            border-radius: 9999px;
            transition: background 0.2s ease;
        }
        *:hover::-webkit-scrollbar-thumb {
            background: rgba(194, 65, 12, 0.35);
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(194, 65, 12, 0.6) !important;
        }
        ::-webkit-scrollbar-corner {
            background: transparent;
        }

        /* Firefox Smooth Transparent Scrollbar */
        * {
            scrollbar-width: thin;
            scrollbar-color: rgba(139, 126, 116, 0.2) transparent;
        }

        /* --- Primary Buttons: orange accent --- */
        main .bg-blue-600,
        main .bg-blue-500,
        main .bg-indigo-600,
        main .bg-green-600,
        main .bg-emerald-600 {
            background: var(--sz-accent) !important;
            border-color: var(--sz-accent) !important;
        }
        main .bg-blue-600:hover,
        main .bg-blue-500:hover,
        main .bg-indigo-600:hover,
        main .bg-green-600:hover,
        main .bg-emerald-600:hover {
            background: var(--sz-accent-hover) !important;
            border-color: var(--sz-accent-hover) !important;
        }

        /* --- Gradient buttons override --- */
        main .bg-gradient-to-r.from-orange-500,
        main .bg-gradient-to-r.from-blue-500 {
            background: var(--sz-accent) !important;
        }

        /* --- Text links: accent color --- */
        main .text-blue-600,
        main .text-blue-700,
        main .text-indigo-600 {
            color: var(--sz-accent) !important;
        }
        main a.text-blue-600:hover,
        main a.text-blue-700:hover {
            color: var(--sz-accent-hover) !important;
        }

        /* --- Badges: warm tones --- */
        main .bg-blue-100 { background: var(--sz-accent-light) !important; }
        main .text-blue-800 { color: var(--sz-accent) !important; }

        /* --- Pagination active --- */
        main .bg-blue-600.text-white,
        main [class*="bg-blue-600"][class*="border-blue-600"] {
            background: var(--sz-accent) !important;
            border-color: var(--sz-accent) !important;
        }

        /* --- Focus ring: warm orange --- */
        main .focus\:ring-blue-500:focus,
        main .focus\:ring-2:focus {
            --tw-ring-color: rgba(194,65,12,0.2) !important;
        }
        main .focus\:border-blue-500:focus {
            border-color: var(--sz-accent) !important;
        }

        /* --- Stat card icons: warm tint override --- */
        main .text-4xl.text-blue-600 { color: var(--sz-accent) !important; }
        main .text-4xl.text-blue-500 { color: var(--sz-info) !important; }

        /* --- Page headings: warm dark --- */
        main h2, main h3, main h4, main h5 {
            color: var(--sz-fg) !important;
        }
        main .text-gray-600,
        main .text-gray-500 {
            color: var(--sz-muted) !important;
        }

        /* --- Modal & dropdown: warm styling --- */
        .dropdown-menu {
            border: 1px solid var(--sz-border) !important;
            box-shadow: 0 8px 24px rgba(28,15,10,0.08) !important;
            border-radius: 12px !important;
        }

        /* --- SweetAlert theme override --- */
        .swal2-popup {
            border-radius: 16px !important;
        }
        .swal2-confirm {
            background: var(--sz-accent) !important;
            border-radius: 8px !important;
        }

        /* --- Print: keep it clean --- */
        @media print {
            main, main > div { background: white !important; }
            #sidebar, header { display: none !important; }
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Select2 Tailwind Customization */
        .select2-container .select2-selection--single {
            height: 44px !important;
            border-color: #e8e0d6 !important;
            border-radius: 0.75rem !important; /* rounded-xl */
            display: flex !important;
            align-items: center !important;
            padding-left: 0.5rem;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 42px !important;
            right: 10px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #1c0f0a !important;
            font-size: 0.75rem !important; /* text-xs */
            font-weight: 500 !important;
            line-height: 44px !important;
        }
        .select2-container--open .select2-dropdown--below {
            border-color: #e8e0d6 !important;
            border-radius: 0 0 0.75rem 0.75rem !important;
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1) !important;
        }
        .select2-results__option {
            font-size: 0.75rem !important;
            padding: 10px 16px !important;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #c2410c !important; /* orange-700 */
        }
        .select2-search--dropdown .select2-search__field {
            border-radius: 0.5rem !important;
            border-color: #e8e0d6 !important;
            padding: 8px !important;
            font-size: 0.75rem !important;
            outline: none !important;
        }
        .select2-search--dropdown .select2-search__field:focus {
            border-color: #c2410c !important;
        }
    </style>
</head>

<body style="background-color: #faf8f5;">
    <div class="w-full">
        <div class="flex min-h-screen">
            @auth
                @if (!auth()->user()->hasRole('muzakki'))
                    @include('components.sidebar', [
                        'user' => auth()->user(),
                        'currentRoute' => request()->route()->getName() ?? '',
                    ])
                    <main class="flex-1 min-w-0 w-full">
                        @include('components.navbar')
                        <div>
                            
                    @yield('content')
                        </div>
                    </main>
                @else
                    <main class="w-full muzakki-layout">
                        <div>
                            @include('components.alerts')
                            
                    @yield('content')
                        </div>
                    </main>
                @endif
            @else
                <main class="w-full">
                    @include('components.navbar')
                    <div>
                        @include('components.alerts')
                        
                    @yield('content')
                    </div>
                </main>
            @endauth
        </div>
    </div>

    

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // CSRF Token for AJAX requests
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken && typeof axios !== 'undefined') {
                window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
                window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken.content;
            }

            // SweetAlert Notifications
            @if(session('success'))
                Swal.fire({ icon: 'success', title: 'Berhasil', text: @json(session('success')), confirmButtonColor: '#ea580c' });
            @elseif(session('error'))
                Swal.fire({ icon: 'error', title: 'Gagal', text: @json(session('error')), confirmButtonColor: '#ea580c' });
            @elseif(session('warning'))
                Swal.fire({ icon: 'warning', title: 'Perhatian', text: @json(session('warning')), confirmButtonColor: '#ea580c' });
            @elseif(session('info'))
                Swal.fire({ icon: 'info', title: 'Informasi', text: @json(session('info')), confirmButtonColor: '#ea580c' });
            @endif

            @if($errors->any())
                const errors = {!! json_encode($errors->all()) !!};
                const errorHtml = `<ul class="text-left list-disc pl-4 text-sm text-gray-700">${errors.map(e => `<li class="mb-1">${e}</li>`).join('')}</ul>`;
                Swal.fire({ icon: 'error', title: 'Terjadi Kesalahan', html: errorHtml, confirmButtonColor: '#ea580c' });
            @endif

            // Simple Event Delegation for Confirmations
            document.body.addEventListener('click', function(e) {
                // Find nearest element with onclick="confirm(...)"
                let target = e.target.closest('[onclick*="confirm("]');
                if (!target) return;

                const match = target.getAttribute('onclick').match(/confirm\s*\(\s*(['"])(.*?)\1\s*\)/);
                if (match) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    
                    Swal.fire({
                        icon: 'warning',
                        title: 'Anda yakin?',
                        text: match[2],
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Ya, lanjutkan',
                        cancelButtonText: 'Batal',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            target.removeAttribute('onclick');
                            if (target.tagName === 'A') {
                                window.location.href = target.href;
                            } else if (target.type === 'submit' && target.form) {
                                target.form.submit();
                            }
                        }
                    });
                }
            }, true);

            // Simple Event Delegation for form onsubmit="confirm(...)"
            document.body.addEventListener('submit', function(e) {
                const form = e.target;
                const onsubmitAttr = form.getAttribute('onsubmit');
                
                if (onsubmitAttr && onsubmitAttr.includes('confirm(')) {
                    e.preventDefault();
                    const match = onsubmitAttr.match(/confirm\s*\(\s*(['"])(.*?)\1\s*\)/);
                    if (match) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Anda yakin?',
                            text: match[2],
                            showCancelButton: true,
                            confirmButtonColor: '#dc2626',
                            cancelButtonColor: '#6b7280',
                            confirmButtonText: 'Ya, lanjutkan',
                            cancelButtonText: 'Batal',
                            reverseButtons: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.removeAttribute('onsubmit');
                                form.submit();
                            }
                        });
                    }
                } else {
                    // Loading state
                    const btn = form.querySelector('button[type="submit"]');
                    if (btn) {
                        btn.disabled = true;
                        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
                    }
                }
            });

            // Clean modal backdrops on hide
            document.querySelectorAll('.modal').forEach(modal => {
                modal.addEventListener('hidden.bs.modal', function() {
                    if (!document.querySelector('.modal.show')) {
                        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                        document.body.classList.remove('modal-open');
                        document.body.style.overflow = '';
                        document.body.style.paddingRight = '';
                    }
                });
            });

            // Auto-hide Bootstrap alerts
            setTimeout(() => {
                document.querySelectorAll('.alert-dismissible').forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Apply Select2 to all select elements by default, except those with 'no-select2' class
            $('select:not(.no-select2)').select2({
                width: '100%',
                language: {
                    noResults: function() {
                        return "Data tidak ditemukan";
                    }
                }
            });
        });
    </script>
    @stack('scripts')
</body>

</html>
