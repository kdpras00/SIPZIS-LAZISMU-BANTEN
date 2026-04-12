<nav class="fixed w-full z-50 transition-all duration-300 font-poppins bold" id="navbar">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center h-16">
            <!-- SIPZ Title - Left Side -->
            <div class="flex items-center">
                <a href="/" class="flex-shrink-0 flex items-center">

                    <div class="flex items-center ml-0 md:ml-4">
                        <img src="{{ asset('storage/logo/logo-lazismu.png') }}" alt="Logo Lazismu" class="h-10 w-auto -mt-2">
                    </div>
                </a>
            </div>

            <!-- Mobile Icons - Notification and Hamburger Menu Buttons -->
            <div class="md:hidden flex items-center space-x-3">
                <!-- Notification Icon for Mobile -->
                @auth
                    @if (Auth::user()->role === 'muzakki' && Auth::user()->muzakki)
                        @php
                            $unreadNotificationsCount = Auth::user()->muzakki->unread_notifications_count;
                        @endphp
                        <div class="relative" id="mobile-notification-container">
                            <button type="button" class="flex text-sm rounded-full focus:outline-none"
                                id="mobile-notification-button">
                                <div
                                    class="h-6 w-6 rounded-full bg-yellow-500 flex items-center justify-center text-white hover:bg-yellow-600 transition-colors">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                        </path>
                                    </svg>
                                </div>
                            </button>
                            <!-- Notification Badge -->
                            @if ($unreadNotificationsCount > 0)
                                <span
                                    class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-4 w-4 flex items-center justify-center">
                                    {{ $unreadNotificationsCount }}
                                </span>
                            @endif
                        </div>
                    @endif
                @endauth

                <!-- Hamburger Menu Button - Mobile -->
                <button id="mobile-menu-button" class="text-gray-800 hover:text-orange-600 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>

            <!-- Navigation Links - Center -->

            <div class="hidden md:flex items-center justify-center flex-1">
                <div class="flex items-center space-x-8" id="href-navbar">
                    {{-- Logic for active state: Check Route or $activePage variable --}}
                    @php $isActive = fn($route, $page) => request()->routeIs($route) || (isset($activePage) && $activePage === $page); @endphp
                    
                    {{-- <a href="{{ route('home') }}"
                        class="px-3 py-2 text-gray-800 hover:border-b-2 hover:border-orange-600 hover:text-orange-600 transition duration-300 navbar-link {{ $isActive('home', 'home') ? 'border-b-2 border-white' : '' }}">Home</a> --}}
                    <a href="{{ route('tentang') }}"
                        class="px-3 py-2 text-gray-800 hover:border-b-2 hover:border-orange-600 hover:text-orange-600 transition duration-300 navbar-link {{ $isActive('tentang*', 'tentang') ? 'border-b-2 border-white' : '' }}">Tentang</a>
                    <a href="{{ route('program') }}"
                        class="px-3 py-2 text-gray-800 hover:border-b-2 hover:border-orange-600 hover:text-orange-600 transition duration-300 navbar-link {{ $isActive('program*', 'program') ? 'border-b-2 border-white' : '' }}">Program</a>
                    <a href="{{ route('berita') }}"
                        class="px-3 py-2 text-gray-800 hover:border-b-2 hover:border-orange-600 hover:text-orange-600 transition duration-300 navbar-link {{ $isActive('berita*', 'berita') ? 'border-b-2 border-white' : '' }}">Berita</a>
                    <a href="{{ route('artikel.all') }}"
                        class="px-3 py-2 text-gray-800 hover:border-b-2 hover:border-orange-600 hover:text-orange-600 transition duration-300 navbar-link {{ $isActive('artikel*', 'artikel') ? 'border-b-2 border-white' : '' }}">Artikel</a>
                </div>
            </div>

            <!-- Mobile Menu (Hidden by default) -->
            <div id="mobile-menu" class="md:hidden fixed inset-0 bg-white bg-opacity-[0.98] z-40 hidden">
                <div class="flex flex-col h-full">
                    <div class="flex justify-between items-center p-4 border-b border-gray-200">
                        <div class="flex items-center">
                            <img src="{{ asset('storage/logo/logo-lazismu.png') }}" alt="Logo Lazismu" class="h-10 w-auto">
                        </div>
                        <button id="close-mobile-menu" class="text-gray-800 hover:text-orange-600 focus:outline-none">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="flex flex-col py-4 space-y-4 overflow-y-auto">
                        <!-- <a href="{{ route('home') }}"
                            class="px-4 py-2 text-gray-800 hover:bg-orange-50 hover:text-orange-600 transition duration-300 navbar-link">Home</a> -->
                        <a href="{{ route('tentang') }}"
                            class="px-4 py-2 text-gray-800 hover:bg-orange-50 hover:text-orange-600 transition duration-300 navbar-link">Tentang</a>
                        <a href="{{ route('program') }}"
                            class="px-4 py-2 text-gray-800 hover:bg-orange-50 hover:text-orange-600 transition duration-300 navbar-link">Program</a>
                        <a href="{{ route('berita') }}"
                            class="px-4 py-2 text-gray-800 hover:bg-orange-50 hover:text-orange-600 transition duration-300 navbar-link">Berita</a>
                        <a href="{{ route('artikel.all') }}"
                            class="px-4 py-2 text-gray-800 hover:bg-orange-50 hover:text-orange-600 transition duration-300 navbar-link">Artikel</a>

                        @auth
                            @if (Auth::user()->role === 'admin')
                                <div class="border-t border-gray-200 mt-4 pt-4">
                                    <a href="{{ route('dashboard') }}"
                                        class="block px-4 py-2 text-gray-800 hover:bg-orange-50 hover:text-orange-600 transition duration-300">
                                        Dashboard Admin
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}" class="px-4 py-2">
                                        @csrf
                                        <button type="submit"
                                            class="w-full text-left text-gray-800 hover:bg-orange-50 hover:text-orange-600 transition duration-300">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="border-t border-gray-200 mt-4 pt-4">
                                    <a href="{{ route('dashboard') }}"
                                        class="block px-4 py-2 text-gray-800 hover:bg-orange-50 hover:text-orange-600 transition duration-300">
                                        Profil
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}" class="px-4 py-2">
                                        @csrf
                                        <button type="submit"
                                            class="w-full text-left text-gray-800 hover:bg-orange-50 hover:text-orange-600 transition duration-300">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @else
                            <div class="border-t border-gray-200 mt-4 pt-4">
                                <a href="{{ route('login') }}"
                                    class="block px-4 py-2 border-2 border-orange-600 text-orange-600 rounded-lg hover:bg-orange-50 hover:text-orange-700 transition duration-300 text-center mx-4 mb-2">
                                    Masuk
                                </a>
                                <a href="{{ route('register') }}"
                                    class="block px-4 py-2 text-gray-800 hover:bg-orange-50 hover:text-orange-600 transition duration-300">
                                    Daftar
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Profile/Login Section - Right Side -->
            <div class="hidden md:flex items-center space-x-2">
                @auth
                    <!-- Show different navigation based on user role -->
                    @if (Auth::user()->role === 'admin')
                        <!-- Admin Navigation -->
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('dashboard') }}"
                                class="text-gray-800 hover:text-orange-600 transition duration-300 font-medium">
                                Dashboard Admin
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="bg-orange-600 text-white px-4 py-2 rounded-full font-medium hover:bg-orange-700 transition duration-300">
                                    Logout
                                </button>
                            </form>
                        </div>
                    @else
                        <!-- Muzakki Navigation -->
                        <!-- Notification Icon -->
                        @if (Auth::user()->muzakki)
                            @php
                                $unreadNotificationsCount = Auth::user()->muzakki->unread_notifications_count;
                            @endphp
                            <div class="relative" id="notification-container">
                                <button type="button" class="flex text-sm rounded-full focus:outline-none"
                                    id="notification-button">
                                    <div
                                        class="h-8 w-8 rounded-full bg-yellow-500 flex items-center justify-center text-white hover:bg-yellow-600 transition-colors">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                            </path>
                                        </svg>
                                    </div>
                                </button>
                                <!-- Notification Badge -->
                                @if ($unreadNotificationsCount > 0)
                                    <span
                                        class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">
                                        {{ $unreadNotificationsCount }}
                                    </span>
                                @endif

                                <!-- Notification Popup -->
                                <div id="notification-popup" style="width: 32rem; top: 100%; margin-top: 0.75rem;"
                                    class="origin-top-right absolute right-0 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-10 hidden z-50 transition-all duration-200 ease-out transform opacity-0 scale-95">
                                    <div class="flex items-center justify-between border-b border-gray-200 pb-3 px-4 pt-4">
                                        <h3 class="text-lg font-semibold text-gray-900">Notifikasi</h3>
                                        <button id="close-notification"
                                            class="text-gray-400 hover:text-gray-500 rounded-full p-1 hover:bg-gray-100 transition-colors duration-200">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <div id="notification-content" class="max-h-[28rem] overflow-y-auto p-4"
                                        style="scrollbar-width: thin;">
                                        <!-- Notifications will be loaded here via AJAX -->
                                        <div class="flex justify-center py-8">
                                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-orange-600">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Profile Dropdown -->
                        <div class="relative">
                            <div>
                                <button type="button" class="flex text-sm rounded-full focus:outline-none"
                                    id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                    <span class="sr-only">Open user menu</span>
                                    <div
                                        class="h-8 w-8 rounded-full bg-orange-500 flex items-center justify-center text-white hover:bg-orange-600 transition-colors">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                            </path>
                                        </svg>
                                    </div>
                                </button>
                            </div>

                            <!-- Dropdown menu -->
                            <div class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 hidden"
                                role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button"
                                tabindex="-1" id="user-dropdown">
                                <a href="{{ route('dashboard') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem"
                                    tabindex="-1">Profil</a>
                                <!-- <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1">Dashboard</a> -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                        role="menuitem" tabindex="-1">Logout</button>
                                </form>
                            </div>
                        </div>
                    @endif
                @else
                    <!-- Login/Register Buttons -->
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}"
                            class="text-orange-600 border border-orange-600 px-4 py-2 rounded-full hover:bg-orange-50 hover:text-orange-700 transition duration-300 font-medium">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}"
                            class="bg-orange-600 text-white px-4 py-2 rounded-full font-medium hover:bg-orange-700 transition duration-300">
                            Daftar
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
    </div>
</nav>

<script>
    const navbar = document.getElementById('navbar');
    const navbarTitle = document.getElementById('navbar-title');
    const navbarLinks = document.querySelectorAll('.navbar-link');
    const beranda = document.getElementById('beranda');

    // Profile dropdown toggle
    document.addEventListener('DOMContentLoaded', function() {
        const userMenuButton = document.getElementById('user-menu-button');
        const userDropdown = document.getElementById('user-dropdown');

        if (userMenuButton && userDropdown) {
            userMenuButton.addEventListener('click', function() {
                userDropdown.classList.toggle('hidden');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                if (!userMenuButton.contains(event.target) && !userDropdown.contains(event.target)) {
                    userDropdown.classList.add('hidden');
                }
            });
        }
    });

    // Smooth scrolling for navbar links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Periksa apakah elemen 'beranda' ada di halaman
    if (beranda) {
        let isTransparent = true;

        
        // Fungsi untuk membuat navbar transparan (putih dengan efek blur)
        function setNavbarTransparent() {
            if (!isTransparent) {
                navbar.classList.remove('bg-white', 'shadow-md');
                navbar.classList.add('bg-white/90', 'backdrop-blur-md');
                isTransparent = true;
            }
        }

        // Fungsi untuk membuat navbar solid (putih solid)
        function setNavbarSolid() {
            if (isTransparent) {
                navbar.classList.remove('bg-transparent', 'bg-white/90', 'backdrop-blur-md');
                navbar.classList.add('bg-white', 'shadow-md');
                isTransparent = false;
            }
        }


        // Periksa lokasi hash saat ini
        function checkCurrentHash() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            const berandaHeight = beranda.offsetHeight;

            if (window.location.hash !== '#beranda' && window.location.hash !== '') {
                setNavbarSolid();
            } else if (scrollTop < berandaHeight) {
                setNavbarTransparent();
            } else {
                setNavbarSolid();
            }
        }

        // Jalankan saat halaman di-scroll
        window.addEventListener('scroll', checkCurrentHash);

        // Jalankan saat hash berubah
        window.addEventListener('hashchange', checkCurrentHash);

        // Jalankan saat halaman dimuat
        window.addEventListener('load', checkCurrentHash);

    } else {
        // Jika tidak ada elemen beranda, buat navbar selalu solid
        navbar.classList.remove('bg-transparent');
        navbar.classList.add('bg-white', 'shadow-md');
    }

    // Mobile menu functionality
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const closeMobileMenu = document.getElementById('close-mobile-menu');
        const mobileMenu = document.getElementById('mobile-menu');

        if (mobileMenuButton && closeMobileMenu && mobileMenu) {
            // Open mobile menu
            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.remove('hidden');
                document.body.style.overflow = 'hidden'; // Prevent scrolling when menu is open
            });

            // Close mobile menu
            closeMobileMenu.addEventListener('click', function() {
                mobileMenu.classList.add('hidden');
                document.body.style.overflow = ''; // Re-enable scrolling
            });

            // Close mobile menu when clicking on a link
            const mobileLinks = mobileMenu.querySelectorAll('a, button');
            mobileLinks.forEach(link => {
                link.addEventListener('click', function() {
                    mobileMenu.classList.add('hidden');
                    document.body.style.overflow = '';
                });
            });
        }
    });

    // Notification popup functionality for desktop
    document.addEventListener('DOMContentLoaded', function() {
        const notificationButton = document.getElementById('notification-button');
        const notificationPopup = document.getElementById('notification-popup');
        const closeNotification = document.getElementById('close-notification');
        const notificationContent = document.getElementById('notification-content');

        if (notificationButton && notificationPopup) {
            // Toggle notification popup
            notificationButton.addEventListener('click', function(e) {
                e.stopPropagation();

                // Toggle popup visibility with animation
                if (notificationPopup.classList.contains('hidden')) {
                    // Show popup with animation
                    notificationPopup.classList.remove('hidden');
                    setTimeout(() => {
                        notificationPopup.classList.remove('opacity-0', 'scale-95');
                    }, 10);

                    // Load notifications if popup is opened
                    loadNotifications();
                } else {
                    // Hide popup with animation
                    notificationPopup.classList.add('opacity-0', 'scale-95');
                    setTimeout(() => {
                        notificationPopup.classList.add('hidden');
                    }, 200);
                }
            });

            // Close notification popup
            if (closeNotification) {
                closeNotification.addEventListener('click', function() {
                    notificationPopup.classList.add('opacity-0', 'scale-95');
                    setTimeout(() => {
                        notificationPopup.classList.add('hidden');
                    }, 200);
                });
            }

            // Close popup when clicking outside
            document.addEventListener('click', function(event) {
                if (!notificationButton.contains(event.target) && !notificationPopup.contains(event
                        .target)) {
                    if (!notificationPopup.classList.contains('hidden')) {
                        notificationPopup.classList.add('opacity-0', 'scale-95');
                        setTimeout(() => {
                            notificationPopup.classList.add('hidden');
                        }, 200);
                    }
                }
            });
        }

        // Load notifications via AJAX
        function loadNotifications() {
            fetch('{{ route('notifications.ajax') }}', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                credentials: 'same-origin'
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        notificationContent.innerHTML = data.html;
                    } else {
                        notificationContent.innerHTML =
                            '<div class="text-center py-6 text-gray-500"><p>Gagal memuat notifikasi</p></div>';
                    }
                })
                .catch(error => {
                    console.error('Error loading notifications:', error);
                    notificationContent.innerHTML =
                        '<div class="text-center py-6 text-gray-500"><p>Terjadi kesalahan saat memuat notifikasi</p></div>';
                });
        }
    });

    // Notification popup functionality for mobile
    document.addEventListener('DOMContentLoaded', function() {
        const mobileNotificationButton = document.getElementById('mobile-notification-button');
        const notificationPopup = document.getElementById('notification-popup');
        const closeNotification = document.getElementById('close-notification');
        const notificationContent = document.getElementById('notification-content');

        if (mobileNotificationButton && notificationPopup) {
            // Toggle notification popup from mobile button
            mobileNotificationButton.addEventListener('click', function(e) {
                e.stopPropagation();

                // Toggle popup visibility with animation
                if (notificationPopup.classList.contains('hidden')) {
                    // Show popup with animation
                    notificationPopup.classList.remove('hidden');
                    setTimeout(() => {
                        notificationPopup.classList.remove('opacity-0', 'scale-95');
                    }, 10);

                    // Load notifications if popup is opened
                    loadNotifications();
                } else {
                    // Hide popup with animation
                    notificationPopup.classList.add('opacity-0', 'scale-95');
                    setTimeout(() => {
                        notificationPopup.classList.add('hidden');
                    }, 200);
                }
            });

            // Close notification popup
            if (closeNotification) {
                closeNotification.addEventListener('click', function() {
                    notificationPopup.classList.add('opacity-0', 'scale-95');
                    setTimeout(() => {
                        notificationPopup.classList.add('hidden');
                    }, 200);
                });
            }

            // Close popup when clicking outside
            document.addEventListener('click', function(event) {
                if (!mobileNotificationButton.contains(event.target) && !notificationPopup.contains(
                        event.target)) {
                    if (!notificationPopup.classList.contains('hidden')) {
                        notificationPopup.classList.add('opacity-0', 'scale-95');
                        setTimeout(() => {
                            notificationPopup.classList.add('hidden');
                        }, 200);
                    }
                }
            });
        }

        // Load notifications via AJAX
        function loadNotifications() {
            fetch('{{ route('notifications.ajax') }}', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                credentials: 'same-origin'
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        notificationContent.innerHTML = data.html;
                    } else {
                        notificationContent.innerHTML =
                            '<div class="text-center py-6 text-gray-500"><p>Gagal memuat notifikasi</p></div>';
                    }
                })
                .catch(error => {
                    console.error('Error loading notifications:', error);
                    notificationContent.innerHTML =
                        '<div class="text-center py-6 text-gray-500"><p>Terjadi kesalahan saat memuat notifikasi</p></div>';
                });
        }
    });
</script>

<style>
    /* Mengubah warna hover tautan menjadi orange */
    .navbar-link:hover {
        color: #ea580c !important;
        border-bottom: 2px solid #ea580c;
    }

    /* Warna teks default untuk navbar solid / transparan sama-sama abu gelap */
    .bg-white .navbar-link, .bg-white\/90 .navbar-link {
        color: #1f2937;
    }

    .navbar-link.border-orange-600 {
        color: #ea580c !important;
        border-color: #ea580c !important;
    }


    /* Warna teks default untuk navbar transparan */
    .bg-transparent .navbar-link {
        color: white;
    }

    /* Special styling for payment link */
    .navbar-payment-link {
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .navbar-payment-link:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }
</style>
