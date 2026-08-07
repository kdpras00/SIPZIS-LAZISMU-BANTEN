@php
    $user = $user ?? auth()->user();
    $currentRoute = $currentRoute ?? Route::currentRouteName();
    if (!$user) return;

    $hour = (int) now('Asia/Jakarta')->format('H');
    $greeting = $hour < 11 ? 'Selamat Pagi' : ($hour < 15 ? 'Selamat Siang' : ($hour < 18 ? 'Selamat Sore' : 'Selamat Malam'));
    $firstName = explode(' ', $user->name)[0];
@endphp

<aside id="sidebar" class="flex flex-col h-screen overflow-y-auto overflow-x-hidden scrollbar-hide" style="background: #faf8f5; border-right: 1px solid #f0ece6;">

    {{-- Brand --}}
    <div class="flex items-center px-5" style="flex-shrink: 0; border-bottom: 1px solid #f0ece6; height: 68px; box-sizing: border-box;">
        <a href="{{ route('dashboard') }}" class="block no-underline" aria-label="Halaman Utama Dashboard">
            <img src="{{ asset('img/logo.png') }}" alt="Logo Lazismu Banten" width="160" height="50" loading="lazy" style="height: 46px; width: auto; max-width: 180px; object-fit: contain; object-position: left;">
        </a>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-4 pt-4 pb-2">

        {{-- Main --}}
        <div class="mb-6">
            <a href="{{ route('dashboard') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors duration-200 no-underline mb-0.5 {{ $currentRoute === 'dashboard' ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill text-base" style="min-width: 20px;"></i>
                <span>Dashboard</span>
            </a>
        </div>

        @if ($user->role === 'admin')
        {{-- Data Management --}}
        <div class="mb-6">
            <a href="{{ route('muzakki.index') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors duration-200 no-underline mb-0.5 {{ str_starts_with($currentRoute, 'muzakki.') && !str_contains($currentRoute, 'dashboard') ? 'active' : '' }}">
                <i class="bi bi-people-fill text-base" style="min-width: 20px;"></i>
                <span>Muzakki</span>
            </a>

            <a href="{{ route('mustahik.index') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors duration-200 no-underline mb-0.5 {{ str_starts_with($currentRoute, 'mustahik.') ? 'active' : '' }}">
                <i class="bi bi-person-hearts text-base" style="min-width: 20px;"></i>
                <span>Mustahik</span>
            </a>

            <a href="{{ route('payments.index') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors duration-200 no-underline mb-0.5 {{ str_starts_with($currentRoute, 'payments.') ? 'active' : '' }}">
                <i class="bi bi-credit-card-fill text-base" style="min-width: 20px;"></i>
                <span>Pembayaran ZIS</span>
            </a>

            <a href="{{ route('distributions.index') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors duration-200 no-underline mb-0.5 {{ str_starts_with($currentRoute, 'distributions.') ? 'active' : '' }}">
                <i class="bi bi-archive-fill text-base" style="min-width: 20px;"></i>
                <span>Distribusi ZIS</span>
            </a>
        </div>

        {{-- Reports --}}
        <div class="mb-6" style="border-top: 1px solid #f0ece6; padding-top: 1.25rem;">
            <a href="{{ route('reports.incoming') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors duration-200 no-underline mb-0.5 {{ $currentRoute === 'reports.incoming' ? 'active' : '' }}">
                <i class="bi bi-arrow-down-circle-fill text-base" style="min-width: 20px;"></i>
                <span>Laporan Masuk</span>
            </a>

            <a href="{{ route('reports.outgoing') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors duration-200 no-underline mb-0.5 {{ $currentRoute === 'reports.outgoing' ? 'active' : '' }}">
                <i class="bi bi-arrow-up-circle-fill text-base" style="min-width: 20px;"></i>
                <span>Laporan Keluar</span>
            </a>
        </div>

        {{-- Content --}}
        <div class="mb-6" style="border-top: 1px solid #f0ece6; padding-top: 1.25rem;">
            <a href="{{ route('admin.news.index') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors duration-200 no-underline mb-0.5 {{ str_starts_with($currentRoute, 'admin.news.') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-richtext text-base" style="min-width: 20px;"></i>
                <span>Berita</span>
            </a>

            <a href="{{ route('admin.artikel.index') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors duration-200 no-underline mb-0.5 {{ str_starts_with($currentRoute, 'admin.artikel.') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-text text-base" style="min-width: 20px;"></i>
                <span>Artikel</span>
            </a>

            <a href="{{ route('admin.campaigns.index') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors duration-200 no-underline mb-0.5 {{ str_starts_with($currentRoute, 'admin.campaigns.') ? 'active' : '' }}">
                <i class="bi bi-megaphone text-base" style="min-width: 20px;"></i>
                <span>Campaign</span>
            </a>

            <a href="{{ route('admin.programs.index') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors duration-200 no-underline mb-0.5 {{ str_starts_with($currentRoute, 'admin.programs.') ? 'active' : '' }}">
                <i class="bi bi-grid-fill text-base" style="min-width: 20px;"></i>
                <span>Program</span>
            </a>
        </div>
        @endif
    </nav>
</aside>

<div id="sidebar-overlay" class="fixed inset-0 bg-black/30 z-40 hidden opacity-0 transition-opacity duration-300"></div>

<style>
    #sidebar {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    #sidebar::-webkit-scrollbar {
        display: none;
    }

    .sidebar-link {
        color: #8b7e74;
        position: relative;
    }
    .sidebar-link:hover {
        color: #1c0f0a;
        background: #f0ece6;
    }
    .sidebar-link.active {
        color: #c2410c;
        background: #fff7ed;
        font-weight: 600;
    }
    .sidebar-link.active i {
        color: #c2410c;
    }

    @media (max-width: 767.98px) {
        #sidebar {
            position: fixed !important;
            left: 0 !important;
            top: 0 !important;
            bottom: 0 !important;
            width: 272px !important;
            max-width: 272px !important;
            z-index: 1050 !important;
            transform: translateX(-100%);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 4px 0 24px rgba(0,0,0,0.08);
        }
        #sidebar.show {
            transform: translateX(0) !important;
        }
        #sidebar-overlay.show {
            display: block !important;
            opacity: 1 !important;
        }
    }

    @media (min-width: 768px) {
        #sidebar {
            position: sticky !important;
            top: 0 !important;
            width: 272px !important;
            min-width: 272px !important;
            flex-shrink: 0 !important;
        }
        #sidebar.sidebar-collapsed {
            width: 0 !important;
            min-width: 0 !important;
            overflow: hidden !important;
            padding: 0 !important;
        }
    }
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const sidebar = document.getElementById("sidebar");
    const toggleBtn = document.getElementById("sidebarToggle");
    const overlay = document.getElementById("sidebar-overlay");
    const main = document.querySelector("main");
    const aside = sidebar ? sidebar.parentElement : null;

    if (!sidebar || !toggleBtn) return;

    toggleBtn.addEventListener("click", function(e) {
        e.preventDefault();
        if (window.innerWidth < 768) {
            sidebar.classList.toggle("show");
            overlay && overlay.classList.toggle("show");
        } else {
            aside && aside.classList.toggle("sidebar-collapsed");
            main && main.classList.toggle("sidebar-collapsed");
        }
    });

    overlay && overlay.addEventListener("click", function() {
        sidebar.classList.remove("show");
        overlay.classList.remove("show");
    });

    window.addEventListener("resize", function() {
        if (window.innerWidth >= 768) {
            sidebar.classList.remove("show");
            overlay && overlay.classList.remove("show");
        } else {
            aside && aside.classList.remove("sidebar-collapsed");
            main && main.classList.remove("sidebar-collapsed");
        }
    });
});
</script>
