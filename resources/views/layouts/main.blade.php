<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="title" content="{{ isset($title) && $title ? $title . ' - Lazismu Banten' : 'Lazismu Banten - Sistem Informasi Pengelolaan Zakat' }}">
    <meta name="description" content="@yield('meta_description', 'Platform digital pengelolaan Zakat, Infaq, dan Sedekah secara mudah, transparan, dan sesuai syariat Islam dari Lazismu Banten.')">
    <meta name="application-name" content="SIPZIS">
    
    
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ isset($title) && $title ? $title . ' - Lazismu Banten' : 'Lazismu Banten - Sistem Informasi Pengelolaan Zakat' }}">
    <meta property="og:description" content="@yield('meta_description', 'Platform digital pengelolaan Zakat, Infaq, dan Sedekah secara mudah, transparan, dan sesuai syariat Islam dari Lazismu Banten.')">
    <meta property="og:image" content="@yield('meta_image', asset('img/logo.png'))">
    <meta property="og:url" content="{{ url()->current() }}">

    
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:title" content="{{ isset($title) && $title ? $title . ' - Lazismu Banten' : 'Lazismu Banten - Sistem Informasi Pengelolaan Zakat' }}">
    <meta property="twitter:description" content="@yield('meta_description', 'Platform digital pengelolaan Zakat, Infaq, dan Sedekah secara mudah, transparan, dan sesuai syariat Islam dari Lazismu Banten.')">
    <meta property="twitter:image" content="@yield('meta_image', asset('img/logo.png'))">

    <link rel="canonical" href="{{ url()->current() }}">

    <link rel="icon" type="image/x-icon" href="{{ asset('img/lazismu-icon.ico') }}">

    <title>{{ isset($title) && $title ? $title . ' - Lazismu Banten' : 'Lazismu Banten' }}</title>
    
    @if(Route::currentRouteName() === 'home')
    <link rel="preload" href="{{ asset('img/masjidbanten.webp') }}" as="image" fetchpriority="high">
    @endif
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    
    @stack('styles')

    <style>
        body{font-family:'Poppins',sans-serif}
        *{-webkit-tap-highlight-color:transparent}
        /* Prevent FOUC - hide animated elements until page loads */
        body:not(.page-loaded) [class*="animate-fadeIn"] {
            opacity: 0 !important;
            visibility: hidden;
        }
    </style>
</head>

<body class="bg-gray-50" style="overflow-x:hidden">
    
    @yield('navbar')

    
    <main>
        
        @yield('content')
    </main>

    
    @php
        $routeName = Route::currentRouteName();
        $showFooterRoutes = ['home', 'tentang', 'berita'];
        $showFooterPattern = '/^(artikel\.)/';
    @endphp

    @if (in_array($routeName, $showFooterRoutes) || preg_match($showFooterPattern, $routeName))
        @include('partials.footer')
    @endif

    
    @yield('scripts')
    
    
    
    
    @stack('scripts')

    @if (!request()->routeIs('admin.login'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const useSweetAlert = {{ auth()->check() && auth()->user()->hasRole('muzakki') ? 'true' : 'false' }};

            if (!useSweetAlert || typeof Swal === 'undefined') {
                return;
            }

            const flash = {
                success: @json(session('success')),
                error: @json(session('error')),
                warning: @json(session('warning')),
                info: @json(session('info'))
            };

            const swalBase = {
                confirmButtonColor: '#047857',
                confirmButtonText: 'OK',
                allowOutsideClick: false,
                buttonsStyling: true,
            };

            if (flash.success) {
                Swal.fire({ ...swalBase, icon: 'success', title: 'Berhasil', text: flash.success });
            } else if (flash.error) {
                Swal.fire({ ...swalBase, icon: 'error', title: 'Gagal', text: flash.error });
            } else if (flash.warning) {
                Swal.fire({ ...swalBase, icon: 'warning', title: 'Perhatian', text: flash.warning });
            } else if (flash.info) {
                Swal.fire({ ...swalBase, icon: 'info', title: 'Informasi', text: flash.info });
            }

            const validationErrors = @json($errors->all());
            if (validationErrors.length) {
                const errorList = validationErrors.map(err => `<li class="mb-1">${err}</li>`).join('');
                Swal.fire({
                    ...swalBase,
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    html: `<ul class="text-left list-disc pl-4 text-sm text-gray-700">${errorList}</ul>`
                });
            }
        });
    </script>
    @endif

    <script>
        // Add page-loaded class to body after page loads to show animated elements
        (function() {
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function() {
                    document.body.classList.add('page-loaded');
                });
            } else {
                // DOMContentLoaded already fired, add class immediately
                document.body.classList.add('page-loaded');
            }
        })();
    </script>
</body>

</html>
