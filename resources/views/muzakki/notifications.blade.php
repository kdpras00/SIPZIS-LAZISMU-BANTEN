@extends($layout ?? 'layouts.main')

@section('title', 'Notifikasi - SIPZIS')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            
            <div class="bg-white px-6 py-4 border-b border-gray-200">
                <div class="mb-4">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali ke Dashboard
                    </a>
                </div>
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl font-bold text-gray-800">Notifikasi</h1>
                    <div class="text-sm text-gray-500">
                        Menampilkan {{ $notifications->total() }} notifikasi
                    </div>
                </div>
            </div>

            
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px">
                    <a href="{{ route('notifications.index', ['filter' => 'all']) }}"
                        class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm {{ $filter === 'all' ? 'active text-orange-600 border-orange-500' : '' }}">
                        Semua
                    </a>
                    <a href="{{ route('notifications.index', ['filter' => 'payment']) }}"
                        class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm {{ $filter === 'payment' ? 'active text-orange-600 border-orange-500' : '' }}">
                        Pembayaran {{ isset($notificationTypes['payment']) ? "({$notificationTypes['payment']->count})" : '' }}
                    </a>
                    <a href="{{ route('notifications.index', ['filter' => 'distribution']) }}"
                        class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm {{ $filter === 'distribution' ? 'active text-orange-600 border-orange-500' : '' }}">
                        Penyaluran {{ isset($notificationTypes['distribution']) ? "({$notificationTypes['distribution']->count})" : '' }}
                    </a>
                    <a href="{{ route('notifications.index', ['filter' => 'program']) }}"
                        class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm {{ $filter === 'program' ? 'active text-orange-600 border-orange-500' : '' }}">
                        Program {{ isset($notificationTypes['program']) ? "({$notificationTypes['program']->count})" : '' }}
                    </a>
                    <a href="{{ route('notifications.index', ['filter' => 'account']) }}"
                        class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm {{ $filter === 'account' ? 'active text-orange-600 border-orange-500' : '' }}">
                        Akun {{ isset($notificationTypes['account']) ? "({$notificationTypes['account']->count})" : '' }}
                    </a>
                    <a href="{{ route('notifications.index', ['filter' => 'reminder']) }}"
                        class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm {{ $filter === 'reminder' ? 'active text-orange-600 border-orange-500' : '' }}">
                        Pengingat {{ isset($notificationTypes['reminder']) ? "({$notificationTypes['reminder']->count})" : '' }}
                    </a>
                    <a href="{{ route('notifications.index', ['filter' => 'message']) }}"
                        class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm {{ $filter === 'message' ? 'active text-orange-600 border-orange-500' : '' }}">
                        Pesan {{ isset($notificationTypes['message']) ? "({$notificationTypes['message']->count})" : '' }}
                    </a>
                </nav>
            </div>

            
            <div class="p-6">
                @if(session()->has('notifications_success'))
                <div class="mb-4 p-4 rounded-lg border border-orange-200 bg-orange-50 text-orange-800">
                    {{ session('notifications_success') }}
                </div>
                @endif

                @if($notifications->count() > 0)
                <div class="space-y-4">
                    @foreach($notifications as $notification)
                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors notification-item" data-type="{{ $notification->type }}">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        @php
                                        $colorClass = [
                                        'payment' => 'green',
                                        'distribution' => 'blue',
                                        'program' => 'purple',
                                        'account' => 'yellow',
                                        'reminder' => 'orange',
                                        'message' => 'indigo'
                                        ][$notification->type] ?? 'gray';

                                        $faIcon = [
                                        'payment' => 'fas fa-wallet',
                                        'distribution' => 'fas fa-hand-holding-heart',
                                        'program' => 'fas fa-mosque',
                                        'account' => 'fas fa-user-circle',
                                        'reminder' => 'fas fa-clock',
                                        'message' => 'fas fa-envelope'
                                        ][$notification->type] ?? 'fas fa-bell';
                                        @endphp
                                        <div class="h-10 w-10 rounded-full bg-{{ $colorClass }}-100 flex items-center justify-center">
                                            <i class="{{ $faIcon }} text-lg text-{{ $colorClass }}-600"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-lg font-medium text-gray-900">
                                            {{ $notification->title }}
                                        </h3>
                                        <p class="text-sm text-gray-500 mt-1">
                                            {{ $notification->created_at->format('d M Y H:i') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-3 ml-14">
                                    <p class="text-sm text-gray-600">
                                        {{ $notification->message }}
                                    </p>
                                    @if(!$notification->is_read)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-2">
                                        Baru
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="ml-4 flex-shrink-0">
                                @php
                                    $actionUrl = $notification->action_url ?? null;
                                @endphp
                                @if($notification->notifiable_type === 'App\Models\Payment' && $actionUrl)
                                <a href="{{ $actionUrl }}"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Lihat Detail
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                
                @if($notifications->hasPages())
                <div class="mt-6">
                    {{ $notifications->links() }}
                </div>
                @endif
                @else
                <div class="text-center py-12">
                    <i class="far fa-bell-slash text-5xl text-gray-400 mx-auto"></i>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada notifikasi</h3>
                    <p class="mt-1 text-sm text-gray-500">Anda tidak memiliki notifikasi saat ini.</p>
                    <div class="mt-6">
                        <a href="{{ route('program') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                            Lakukan Donasi
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab switching functionality is now handled by links
    });
</script>
@endsection