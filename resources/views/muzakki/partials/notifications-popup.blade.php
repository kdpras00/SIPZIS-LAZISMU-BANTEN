

@if($notifications->count() > 0)
<div class="space-y-3">
    
    <form action="{{ route('notifications.markAsRead') }}" method="POST" class="text-right mb-4">
        @csrf
        <button type="submit" class="text-sm text-orange-600 hover:text-orange-700 font-medium transition-colors duration-200">
            Tandai semua sebagai dibaca
        </button>
    </form>

    
    <div class="space-y-3">
        @foreach($notifications as $notification)
        @php
            $actionUrl = $notification->action_url ?? route('notifications.index');
        @endphp
        <a href="{{ $actionUrl }}" class="block border border-gray-200 rounded-xl p-3 hover:bg-gray-50 transition-all duration-200 transform hover:-translate-y-0.5 hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-200 no-underline text-gray-900 {{ $notification->is_read ? 'bg-white' : 'bg-blue-50' }}">
            <div class="flex items-start">
                
                <div class="flex-shrink-0 mt-1">
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

                
                <div class="ml-4 flex-1 min-w-0">
                    <div class="flex items-start justify-between">
                        <h4 class="text-sm font-semibold text-gray-900 truncate">
                            {{ $notification->title }}
                        </h4>
                        @if(!$notification->is_read)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 ml-2 flex-shrink-0">
                            Baru
                        </span>
                        @endif
                    </div>
                    <div class="mt-1">
                        <p class="text-xs text-gray-600 leading-relaxed line-clamp-2">
                            {{ $notification->message }}
                        </p>
                    </div>
                    <div class="mt-2 flex items-center text-xs text-gray-500">
                        <i class="far fa-clock mr-1"></i>
                        <span>{{ $notification->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        </a>
        @endforeach
    </div>

    
    <div class="pt-3 border-t border-gray-200">
        <a href="{{ route('notifications.index') }}" class="text-sm font-medium text-orange-600 hover:text-orange-700 flex items-center justify-center transition-colors duration-200">
            Lihat semua notifikasi
            <i class="fas fa-chevron-right ml-2 text-sm"></i>
        </a>
    </div>
</div>
@else

<div class="text-center py-8">
    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gray-100">
        <i class="far fa-bell-slash text-2xl text-gray-400"></i>
    </div>
    <h3 class="mt-4 text-lg font-medium text-gray-900">Tidak ada notifikasi</h3>
    <p class="mt-2 text-base text-gray-500">Anda tidak memiliki notifikasi saat ini.</p>
    <div class="mt-6">
        <a href="{{ route('program') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors duration-200">
            Lakukan Donasi
        </a>
    </div>
</div>
@endif