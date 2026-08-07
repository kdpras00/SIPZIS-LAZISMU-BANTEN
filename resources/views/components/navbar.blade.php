@php
    $routeName = request()->route()?->getName() ?? '';
    $breadcrumbMap = [
        'dashboard' => 'Overview',
        'muzakki.index' => 'Muzakki',
        'muzakki.create' => 'Tambah Muzakki',
        'muzakki.show' => 'Detail Muzakki',
        'mustahik.index' => 'Mustahik',
        'mustahik.create' => 'Tambah Mustahik',
        'payments.index' => 'Pembayaran',
        'payments.create' => 'Buat Pembayaran',
        'payments.show' => 'Detail Pembayaran',
        'distributions.index' => 'Distribusi',
        'distributions.create' => 'Buat Distribusi',
        'reports.incoming' => 'Laporan Masuk',
        'reports.outgoing' => 'Laporan Keluar',
        'admin.news.index' => 'Berita',
        'admin.artikel.index' => 'Artikel',
        'admin.campaigns.index' => 'Campaign',
        'admin.programs.index' => 'Program',
        'profile.show' => 'Profil',
        'profile.edit' => 'Edit Profil',
        'notifications.index' => 'Notifikasi',
    ];
    $currentPage = $breadcrumbMap[$routeName] ?? 'Dashboard';

    $user = auth()->user();
    $unreadCount = 0;
    if ($user->role === 'muzakki' && $user->muzakki) {
        $unreadCount = $user->muzakki->unread_notifications_count;
    } else {
        $unreadCount = $user->unread_notifications_count;
    }
@endphp

<header class="flex items-center justify-between px-6" style="background: #faf8f5; border-bottom: 1px solid #f0ece6; height: 68px; box-sizing: border-box; position: relative; z-index: 1051;">

    {{-- Left: Toggle + Breadcrumb --}}
    <div class="flex items-center gap-4">
        <button id="sidebarToggle" class="w-9 h-9 rounded-lg flex items-center justify-center transition-colors duration-200 hover:bg-gray-100" style="color: #8b7e74; border: 1px solid #f0ece6; background: transparent; cursor: pointer;" aria-label="Toggle Sidebar">
            <i class="bi bi-list text-lg"></i>
        </button>

        {{-- Desktop breadcrumb --}}
        <nav class="hidden sm:flex items-center gap-1.5 text-sm">
            <a href="{{ route('dashboard') }}" class="no-underline transition-colors hover:opacity-70" style="color: #8b7e74;">Dashboard</a>
            @if($currentPage !== 'Overview' && $currentPage !== 'Dashboard')
                <span style="color: #d1cbc4;">/</span>
                <span class="font-medium" style="color: #1c0f0a;">{{ $currentPage }}</span>
            @endif
        </nav>

        {{-- Mobile: show current page name instead of breadcrumb --}}
        <span class="sm:hidden text-sm font-semibold" style="color: #1c0f0a;">
            {{ $currentPage === 'Overview' ? 'Dashboard' : $currentPage }}
        </span>
    </div>

    {{-- Right: Actions --}}
    <div class="flex items-center gap-3">

        {{-- Notifications --}}
        <div class="dropdown relative">
            <button class="w-9 h-9 rounded-lg flex items-center justify-center transition-colors duration-200 relative hover:bg-gray-100" 
                type="button" data-bs-toggle="dropdown" aria-expanded="false"
                style="color: #8b7e74; border: none; background: transparent; cursor: pointer;" aria-label="Notifikasi">
                <i class="bi bi-bell text-lg"></i>
                @if ($unreadCount > 0)
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full" style="background: #c2410c;"></span>
                @endif
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-xl overflow-hidden" style="min-width: 320px; max-width: 360px; border: 1px solid #f0ece6;">
                <li class="px-4 py-3 flex justify-between items-center" style="border-bottom: 1px solid #f0ece6;">
                    <span class="font-semibold text-sm" style="color: #1c0f0a;">Notifikasi</span>
                    <form action="{{ route('notifications.markAsRead') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-xs no-underline bg-transparent border-0 cursor-pointer" style="color: #c2410c;">Tandai dibaca</button>
                    </form>
                </li>
                <li>
                    <div style="max-height: 300px; overflow-y: auto;">
                        @php
                            $notifications = collect();
                            $limit = 10;
                            if ($user->role === 'muzakki' && $user->muzakki) {
                                $notifications = $user->muzakki->notifications()->latest()->limit($limit)->get();
                            } else {
                                $notifications = $user->notifications()->latest()->limit($limit)->get();
                            }
                        @endphp

                        @if ($notifications->count() > 0)
                            @foreach ($notifications as $notification)
                                <a class="flex items-start gap-3 px-4 py-3 no-underline transition-colors duration-200 hover:bg-gray-50" href="#" style="border-bottom: 1px solid #f8f5f1;">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5"
                                        style="background: {{ $notification->type === 'payment' ? '#f0fdf4' : ($notification->type === 'distribution' ? '#eff6ff' : '#fff7ed') }};">
                                        <i class="bi {{ $notification->type === 'payment' ? 'bi-credit-card text-green-600' : ($notification->type === 'distribution' ? 'bi-box-seam text-blue-600' : 'bi-bell text-orange-600') }} text-xs"></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium mb-0.5 truncate" style="color: #1c0f0a;">{{ $notification->title }}</p>
                                        <p class="text-xs mb-0 line-clamp-2" style="color: #8b7e74;">{{ $notification->message }}</p>
                                        <p class="text-xs mt-1 mb-0" style="color: #b8ada3;">{{ $notification->created_at->diffForHumans() }}</p>
                                    </div>
                                    @if (!$notification->is_read)
                                        <span class="w-2 h-2 rounded-full flex-shrink-0 mt-2" style="background: #c2410c;"></span>
                                    @endif
                                </a>
                            @endforeach
                        @else
                            <div class="px-4 py-6 text-center">
                                <p class="text-sm mb-0" style="color: #8b7e74;">Tidak ada notifikasi</p>
                            </div>
                        @endif
                    </div>
                </li>
                <li>
                    <a class="block text-center py-2.5 text-xs font-semibold no-underline transition-colors hover:bg-gray-50" href="{{ route('notifications.index') }}" style="color: #c2410c; border-top: 1px solid #f0ece6;">
                        Lihat semua
                    </a>
                </li>
            </ul>
        </div>

        {{-- Divider --}}
        <div class="w-px h-6" style="background: #f0ece6;"></div>

        {{-- User Dropdown --}}
        <div class="dropdown relative">
            <button class="flex items-center gap-2.5 px-2 py-1.5 rounded-lg transition-colors duration-200 hover:bg-gray-100"
                type="button" data-bs-toggle="dropdown" aria-expanded="false"
                style="border: none; background: transparent; cursor: pointer;">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white flex-shrink-0" style="background: #c2410c;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <span class="text-sm font-medium hidden sm:inline" style="color: #1c0f0a;">{{ auth()->user()->name }}</span>
                <i class="bi bi-chevron-down text-xs hidden sm:inline" style="color: #8b7e74;"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-xl overflow-hidden" style="border: 1px solid #f0ece6; min-width: 200px;">
                <li class="px-4 py-2.5" style="border-bottom: 1px solid #f0ece6;">
                    <p class="text-xs font-semibold mb-0" style="color: #8b7e74;">{{ ucfirst(auth()->user()->role) }}</p>
                </li>
                <li>
                    <a class="flex items-center gap-2.5 px-4 py-2.5 text-sm no-underline transition-colors hover:bg-gray-50" href="{{ route('profile.show') }}" style="color: #1c0f0a;">
                        <i class="bi bi-person text-base" style="color: #8b7e74;"></i>
                        Profil
                    </a>
                </li>
                <li style="border-top: 1px solid #f0ece6;">
                    <form id="logout-form" method="POST" action="{{ route('logout') }}">@csrf</form>
                    <a class="flex items-center gap-2.5 px-4 py-2.5 text-sm no-underline transition-colors hover:bg-gray-50" href="#"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="color: #1c0f0a;">
                        <i class="bi bi-box-arrow-right text-base" style="color: #8b7e74;"></i>
                        Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const navbar = document.querySelector('header');
    if (!navbar) return;

    const dropdownButtons = navbar.querySelectorAll('[data-bs-toggle="dropdown"]');
    dropdownButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const dropdownMenu = this.parentElement.querySelector('.dropdown-menu');
            if (!dropdownMenu) return;

            const isShown = dropdownMenu.classList.contains('show');
            document.querySelectorAll('.dropdown-menu.show').forEach(m => m !== dropdownMenu && m.classList.remove('show'));
            dropdownMenu.classList.toggle('show');

            if (typeof bootstrap !== 'undefined') {
                try {
                    let bsDropdown = bootstrap.Dropdown.getInstance(button) || new bootstrap.Dropdown(button);
                    isShown ? bsDropdown.hide() : bsDropdown.show();
                } catch(err) {}
            }
        });
    });

    document.addEventListener('click', function(e) {
        if (!navbar.contains(e.target)) {
            navbar.querySelectorAll('.dropdown-menu.show').forEach(m => m.classList.remove('show'));
        }
    });
});
</script>
