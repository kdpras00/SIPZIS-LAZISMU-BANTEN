<nav class="fixed w-full z-50 bg-white border-b border-transparent transition-all duration-300 font-poppins bold" id="navbar">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center h-16 relative">
            
            <div class="flex items-center">
                <a href="{{ url('/') }}" class="flex-shrink-0 flex items-center ml-0 md:ml-4">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo Lazismu Banten" width="180" height="80" class="h-20 w-auto -mt-2">
                </a>
            </div>

            
            <div class="md:hidden flex items-center space-x-3">
                
                @auth
                    @if (Auth::user()->hasRole('muzakki') && Auth::user()->muzakki)
                        @php
                            $unreadNotificationsCount = Auth::user()->muzakki->unread_notifications_count;
                        @endphp
                        <div class="relative" id="mobile-notification-container">
                            <button type="button" class="flex text-sm rounded-full focus:outline-none"
                                id="mobile-notification-button" aria-label="Notifikasi">
                                <div
                                    class="h-6 w-6 rounded-full bg-orange-600 flex items-center justify-center text-white hover:bg-orange-700 transition-colors">
                                    <i class="fas fa-bell"></i>
                                </div>
                            </button>
                            
                            @if ($unreadNotificationsCount > 0)
                                <span
                                    class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-4 w-4 flex items-center justify-center">
                                    {{ $unreadNotificationsCount }}
                                </span>
                            @endif
                        </div>
                    @endif
                @endauth

                
                <button id="mobile-menu-button" class="text-gray-800 hover:text-orange-600 focus:outline-none" aria-label="Buka Menu">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            
            <div class="hidden md:flex items-center justify-center absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 pointer-events-auto">
                <div class="flex items-center space-x-8" id="href-navbar">
                    @php $isActive = fn($route, $page) => request()->routeIs($route) || (isset($activePage) && $activePage === $page); @endphp
                    
                    <a href="{{ route('tentang') }}"
                        class="px-3 py-2 transition duration-300 navbar-link {{ $isActive('tentang*', 'tentang') ? 'active-link' : 'text-gray-800' }}">Tentang</a>
                    <a href="{{ route('program') }}"
                        class="px-3 py-2 transition duration-300 navbar-link {{ $isActive('program*', 'program') ? 'active-link' : 'text-gray-800' }}">Program</a>
                    <a href="{{ route('berita.index') }}"
                        class="px-3 py-2 transition duration-300 navbar-link {{ $isActive('berita*', 'berita') ? 'active-link' : 'text-gray-800' }}">Berita</a>
                    <a href="{{ route('artikel.index') }}"
                        class="px-3 py-2 transition duration-300 navbar-link {{ $isActive('artikel*', 'artikel') ? 'active-link' : 'text-gray-800' }}">Artikel</a>
                </div>
            </div>

            
            <div id="mobile-menu" class="md:hidden fixed inset-0 bg-white bg-opacity-[0.98] z-40 hidden">
                <div class="flex flex-col h-full">
                    <div class="flex justify-between items-center p-4 border-b border-gray-200">
                        <a href="{{ url('/') }}" class="flex items-center">
                            <img src="{{ asset('img/logo.png') }}" alt="Logo Lazismu Banten" width="144" height="64" class="h-9 w-auto">
                        </a>
                        <button id="close-mobile-menu" class="text-gray-800 hover:text-orange-600 focus:outline-none" aria-label="Tutup Menu">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="flex flex-col py-4 space-y-4 overflow-y-auto">
                        
                        <a href="{{ route('tentang') }}"
                            class="block px-4 py-2 text-center {{ $isActive('tentang*', 'tentang') ? 'text-orange-600 font-bold bg-orange-50' : 'text-gray-800 font-medium' }} hover:bg-orange-50 hover:text-orange-600 transition duration-300 navbar-link border-l-4 {{ $isActive('tentang*', 'tentang') ? 'border-orange-600' : 'border-transparent' }}">Tentang</a>
                        <a href="{{ route('program') }}"
                            class="block px-4 py-2 text-center {{ $isActive('program*', 'program') ? 'text-orange-600 font-bold bg-orange-50' : 'text-gray-800 font-medium' }} hover:bg-orange-50 hover:text-orange-600 transition duration-300 navbar-link border-l-4 {{ $isActive('program*', 'program') ? 'border-orange-600' : 'border-transparent' }}">Program</a>
                        <a href="{{ route('berita.index') }}"
                            class="block px-4 py-2 text-center {{ $isActive('berita*', 'berita') ? 'text-orange-600 font-bold bg-orange-50' : 'text-gray-800 font-medium' }} hover:bg-orange-50 hover:text-orange-600 transition duration-300 navbar-link border-l-4 {{ $isActive('berita*', 'berita') ? 'border-orange-600' : 'border-transparent' }}">Berita</a>
                        <a href="{{ route('artikel.index') }}"
                            class="block px-4 py-2 text-center {{ $isActive('artikel*', 'artikel') ? 'text-orange-600 font-bold bg-orange-50' : 'text-gray-800 font-medium' }} hover:bg-orange-50 hover:text-orange-600 transition duration-300 navbar-link border-l-4 {{ $isActive('artikel*', 'artikel') ? 'border-orange-600' : 'border-transparent' }}">Artikel</a>

                        @auth
                            @if (Auth::user()->hasRole('admin'))
                                <div class="border-t border-gray-200 mt-4 pt-4">
                                    <a href="{{ route('dashboard') }}"
                                        class="block px-4 py-2 text-center text-gray-800 hover:bg-orange-50 hover:text-orange-600 transition duration-300 font-medium">
                                        Dashboard Admin
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}" class="px-4 py-2">
                                        @csrf
                                        <button type="submit"
                                            class="w-full text-center font-medium text-gray-800 hover:text-orange-600 transition duration-300">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="border-t border-gray-200 mt-4 pt-4">
                                    <a href="{{ route('dashboard') }}"
                                        class="block px-4 py-2 text-center text-gray-800 hover:bg-orange-50 hover:text-orange-600 transition duration-300 font-medium">
                                        Profil
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}" class="px-4 py-2">
                                        @csrf
                                        <button type="submit"
                                            class="w-full text-center font-medium text-gray-800 hover:text-orange-600 transition duration-300">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @else
                            <div class="border-t border-gray-200 mt-4 pt-4 px-4 space-y-3 pb-6">
                                <a href="{{ route('login') }}"
                                    class="block w-full py-2 border border-orange-600 text-orange-600 font-semibold rounded-lg text-center hover:bg-orange-50 transition duration-300">
                                    Masuk
                                </a>
                                <a href="{{ route('register') }}"
                                    class="block w-full py-2 bg-orange-600 text-white font-semibold rounded-lg text-center hover:bg-orange-700 transition duration-300">
                                    Daftar
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>

            
            <div class="hidden md:flex items-center space-x-2">
                @auth
                    
                    @if (Auth::user()->hasRole('admin'))
                        
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
                        
                        
                        @if (Auth::user()->muzakki)
                            @php
                                $unreadNotificationsCount = Auth::user()->muzakki->unread_notifications_count;
                            @endphp
                            <div class="relative" id="notification-container">
                                <button type="button" class="flex text-sm rounded-full focus:outline-none"
                                    id="notification-button" aria-label="Notifikasi">
                                    <div
                                        class="h-8 w-8 rounded-full bg-orange-600 flex items-center justify-center text-white hover:bg-orange-700 transition-colors">
                                        <i class="fas fa-bell"></i>
                                    </div>
                                </button>
                                
                                @if ($unreadNotificationsCount > 0)
                                    <span
                                        class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">
                                        {{ $unreadNotificationsCount }}
                                    </span>
                                @endif

                                
                                <div id="notification-popup" style="width: 21rem; top: 100%; margin-top: 0.75rem;"
                                    class="origin-top-right absolute right-0 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-10 hidden z-50 transition-all duration-200 ease-out transform opacity-0 scale-95">
                                    <div class="flex items-center justify-between border-b border-gray-200 pb-3 px-4 pt-4">
                                        <h3 class="text-lg font-semibold text-gray-900">Notifikasi</h3>
                                        <button id="close-notification" aria-label="Tutup Notifikasi"
                                            class="text-gray-400 hover:text-gray-500 rounded-full p-1 hover:bg-gray-100 transition-colors duration-200">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <div id="notification-content" class="max-h-[28rem] overflow-y-auto p-4"
                                        style="scrollbar-width: thin;">
                                        
                                        <div class="flex justify-center py-8">
                                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-orange-600">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        
                        <div class="relative">
                            <div>
                                <button type="button" class="flex text-sm rounded-full focus:outline-none"
                                    id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                    <span class="sr-only">Open user menu</span>
                                    @if(Auth::check() && Auth::user()->avatar)
                                        <img src="{{ Auth::user()->avatar }}" alt="Profile Photo" class="h-8 w-8 rounded-full object-cover">
                                    @else
                                        <div class="h-8 w-8 rounded-full bg-orange-500 flex items-center justify-center text-white hover:bg-orange-600 transition-colors">
                                            <i class="fas fa-user-circle"></i>
                                        </div>
                                    @endif
                                </button>
                            </div>

                            
                            <div class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 hidden"
                                role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button"
                                tabindex="-1" id="user-dropdown">
                                <a href="{{ route('dashboard') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem"
                                    tabindex="-1">Profil</a>
                                
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
            userMenuButton.addEventListener('click', function(e) {
                e.stopPropagation();
                userDropdown.classList.toggle('hidden');

                // Close notification popup if open
                const notificationPopup = document.getElementById('notification-popup');
                if (notificationPopup && !notificationPopup.classList.contains('hidden')) {
                    notificationPopup.classList.add('opacity-0', 'scale-95');
                    setTimeout(() => {
                        notificationPopup.classList.add('hidden');
                    }, 200);
                }
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

    let isScrolled = false;

    function updateNavbar() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        if (scrollTop > 20) {
            if (!isScrolled) {
                navbar.classList.add('shadow-sm', 'border-gray-100');
                navbar.classList.remove('border-transparent');
                isScrolled = true;
            }
        } else {
            if (isScrolled) {
                navbar.classList.remove('shadow-sm', 'border-gray-100');
                navbar.classList.add('border-transparent');
                isScrolled = false;
            }
        }
    }

    window.addEventListener('scroll', updateNavbar, { passive: true });
    updateNavbar();

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

                // Close user dropdown if open
                const userDropdown = document.getElementById('user-dropdown');
                if (userDropdown && !userDropdown.classList.contains('hidden')) {
                    userDropdown.classList.add('hidden');
                }

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
        border-bottom: 2px solid #ea580c !important;
    }

    /* Warna teks default untuk navbar solid / transparan sama-sama abu gelap */
    .bg-white .navbar-link:not(.active-link), .bg-white\/90 .navbar-link:not(.active-link) {
        color: #1f2937;
    }

    /* Ini untuk active link di desktop */
    .navbar-link.active-link {
        color: #ea580c !important;
        border-bottom: 2px solid #ea580c !important;
        font-weight: 600;
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
