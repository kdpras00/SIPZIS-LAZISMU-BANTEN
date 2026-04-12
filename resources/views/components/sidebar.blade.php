@php
    $user = $user ?? auth()->user();
    $currentRoute = $currentRoute ?? Route::currentRouteName();
    if (!$user) return;

    $hour = (int) now('Asia/Jakarta')->format('H');
    $greeting = $hour < 11 ? 'Selamat Pagi' : ($hour < 15 ? 'Selamat Siang' : ($hour < 18 ? 'Selamat Sore' : 'Selamat Malam'));
    $firstName = explode(' ', $user->name)[0];
@endphp

<aside id="sidebar" class="flex flex-col h-screen overflow-y-auto overflow-x-hidden scrollbar-hide" style="background: #faf8f5; border-right: 1px solid #f0ece6; width: 100%; max-width: 272px; position: sticky; top: 0;">

    {{-- Brand --}}
    <div class="px-6 pt-6 pb-2">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 no-underline group">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center overflow-hidden">
                <img src="{{ asset('storage/logo/logo-lazismu.png') }}" alt="Logo Lazismu" class="w-full h-auto">
            </div>
            <div>
                <span class="font-bold text-sm tracking-widest" style="color: #1c0f0a; letter-spacing: 0.12em;">SIPZIS</span>
                <span class="block text-xs" style="color: #8b7e74;">Lazismu Banten</span>
            </div>
        </a>
    </div>

    {{-- Personal Greeting --}}
    <div class="px-6 py-4">
        <p class="text-xs mb-0.5" style="color: #8b7e74;">{{ $greeting }},</p>
        <p class="font-semibold text-sm" style="color: #1c0f0a;">{{ $firstName }}</p>
    </div>

    <div class="h-px mx-6" style="background: #f0ece6;"></div>

    {{-- Navigation --}}
    <nav class="flex-1 px-4 pt-4 pb-2">

        {{-- Main --}}
        <div class="mb-5">
            <p class="px-2 mb-2 text-xs font-semibold uppercase tracking-wider" style="color: #b8ada3; letter-spacing: 0.08em;">Utama</p>
            <a href="{{ route('dashboard') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all duration-200 no-underline mb-0.5 {{ $currentRoute === 'dashboard' ? 'active' : '' }}">
                <i class="bi bi-grid-1x2 text-base" style="min-width: 20px;"></i>
                <span>Dashboard</span>
            </a>
        </div>

        @if ($user->role === 'admin')
        {{-- Data Management --}}
        <div class="mb-5">
            <p class="px-2 mb-2 text-xs font-semibold uppercase tracking-wider" style="color: #b8ada3; letter-spacing: 0.08em;">Kelola Data</p>

            <a href="{{ route('muzakki.index') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all duration-200 no-underline mb-0.5 {{ str_starts_with($currentRoute, 'muzakki.') && !str_contains($currentRoute, 'dashboard') ? 'active' : '' }}">
                <i class="bi bi-people text-base" style="min-width: 20px;"></i>
                <span>Muzakki</span>
            </a>

            <a href="{{ route('mustahik.index') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all duration-200 no-underline mb-0.5 {{ str_starts_with($currentRoute, 'mustahik.') ? 'active' : '' }}">
                <i class="bi bi-person-hearts text-base" style="min-width: 20px;"></i>
                <span>Mustahik</span>
            </a>

            <a href="{{ route('payments.index') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all duration-200 no-underline mb-0.5 {{ str_starts_with($currentRoute, 'payments.') ? 'active' : '' }}">
                <i class="bi bi-credit-card text-base" style="min-width: 20px;"></i>
                <span>Pembayaran ZIS</span>
            </a>

            <a href="{{ route('distributions.index') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all duration-200 no-underline mb-0.5 {{ str_starts_with($currentRoute, 'distributions.') ? 'active' : '' }}">
                <i class="bi bi-box-seam text-base" style="min-width: 20px;"></i>
                <span>Distribusi ZIS</span>
            </a>
        </div>

        {{-- Reports --}}
        <div class="mb-5">
            <p class="px-2 mb-2 text-xs font-semibold uppercase tracking-wider" style="color: #b8ada3; letter-spacing: 0.08em;">Laporan</p>

            <a href="{{ route('reports.incoming') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all duration-200 no-underline mb-0.5 {{ $currentRoute === 'reports.incoming' ? 'active' : '' }}">
                <i class="bi bi-arrow-down-circle text-base" style="min-width: 20px;"></i>
                <span>Laporan Masuk</span>
            </a>

            <a href="{{ route('reports.outgoing') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all duration-200 no-underline mb-0.5 {{ $currentRoute === 'reports.outgoing' ? 'active' : '' }}">
                <i class="bi bi-arrow-up-circle text-base" style="min-width: 20px;"></i>
                <span>Laporan Keluar</span>
            </a>
        </div>

        {{-- Content --}}
        <div class="mb-5">
            <p class="px-2 mb-2 text-xs font-semibold uppercase tracking-wider" style="color: #b8ada3; letter-spacing: 0.08em;">Konten</p>

            <a href="{{ route('admin.news.index') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all duration-200 no-underline mb-0.5 {{ str_starts_with($currentRoute, 'admin.news.') ? 'active' : '' }}">
                <i class="bi bi-newspaper text-base" style="min-width: 20px;"></i>
                <span>Berita</span>
            </a>

            <a href="{{ route('admin.artikel.index') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all duration-200 no-underline mb-0.5 {{ str_starts_with($currentRoute, 'admin.artikel.') ? 'active' : '' }}">
                <i class="bi bi-file-text text-base" style="min-width: 20px;"></i>
                <span>Artikel</span>
            </a>

            <a href="{{ route('admin.campaigns.index') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all duration-200 no-underline mb-0.5 {{ str_starts_with($currentRoute, 'admin.campaigns.') ? 'active' : '' }}">
                <i class="bi bi-megaphone text-base" style="min-width: 20px;"></i>
                <span>Campaign</span>
            </a>

            <a href="{{ route('admin.programs.index') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all duration-200 no-underline mb-0.5 {{ str_starts_with($currentRoute, 'admin.programs.') ? 'active' : '' }}">
                <i class="bi bi-grid text-base" style="min-width: 20px;"></i>
                <span>Program</span>
            </a>
        </div>
        @endif
    </nav>

    {{-- Bottom User Card --}}
    <div class="mt-auto px-4 pb-5">
        <div class="h-px mb-4" style="background: #f0ece6;"></div>
        <div class="flex items-center gap-3 px-2">
            <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold text-white flex-shrink-0" style="background: #c2410c;">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div class="min-w-0">
                <p class="text-sm font-medium truncate mb-0" style="color: #1c0f0a;">{{ $user->name }}</p>
                <p class="text-xs truncate mb-0" style="color: #8b7e74;">{{ ucfirst($user->role) }}</p>
            </div>
        </div>
    </div>
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
        border-left: 3px solid #c2410c;
        padding-left: 9px;
    }
    .sidebar-link.active i {
        color: #c2410c;
    }

    @media (max-width: 767.98px) {
        #sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            z-index: 1050;
            transform: translateX(-100%);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 4px 0 24px rgba(0,0,0,0.08);
        }
        #sidebar.show {
            transform: translateX(0);
        }
        #sidebar-overlay.show {
            display: block;
            opacity: 1;
        }
    }

    @media (min-width: 768px) {
        #sidebar.collapsed {
            position: fixed;
            left: 0; top: 0; bottom: 0;
            z-index: 1050;
        }
        aside.sidebar-collapsed {
            width: 0 !important;
            overflow: hidden;
            padding: 0;
        }
    }
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const sidebar = document.getElementById("sidebar");
    const toggleBtn = document.getElementById("sidebarToggle");
    const overlay = document.getElementById("sidebar-overlay");
    const main = document.querySelector("main");
    const aside = sidebar ? sidebar.closest("aside") : null;

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
