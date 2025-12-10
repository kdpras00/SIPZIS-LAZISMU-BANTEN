@php
    $user = auth()->user();
    $showReminder = false;
    $messages = [];
    $actions = [];

    if ($user) {
        // Check 2FA
        if (!$user->two_factor_enabled) {
            $messages[] = 'Aktifkan 2FA untuk keamanan ekstra.';
            $actions[] = [
                'label' => 'Aktifkan 2FA',
                'url' => route('dashboard.two-factor.setup'),
                'class' => 'text-green-700 bg-green-100 hover:bg-green-200'
            ];
        }

        // Check Profile Completeness (only for Muzakki)
        if ($user->role === 'muzakki' && $user->muzakki) {
            $completeness = $user->muzakki->profile_completeness;
            if ($completeness < 100) {
                $messages[] = 'Lengkapi profil Anda (' . $completeness . '%).';
                // Avoid duplicate actions if we want to keep it simple, or add both.
                // Let's add it as a separate action.
                $actions[] = [
                    'label' => 'Lengkapi Profil',
                    'url' => route('profile.edit'),
                    'class' => 'text-blue-700 bg-blue-100 hover:bg-blue-200'
                ];
            }
        }

        if (!empty($messages)) {
            $showReminder = true;
        }
    }
@endphp

@if ($showReminder)
    <div id="security-reminder" class="fixed top-24 right-4 z-50 w-80 bg-white rounded-xl shadow-2xl border border-gray-100 overflow-hidden animate-fade-in-up transform transition-all duration-300 hover:scale-[1.02]" style="display: none; animation: fadeInUp 0.5s ease-out;">
        <!-- Header -->
        <div class="bg-gradient-to-r from-green-500 to-green-600 px-4 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-2 text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-semibold text-sm">Perhatian</span>
            </div>
            <button id="close-reminder" class="text-white/80 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Content -->
        <div class="p-4 bg-white">
            <div class="space-y-3">
                @foreach($messages as $message)
                    <div class="flex items-start space-x-3 text-sm text-gray-600">
                        <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ $message }}</span>
                    </div>
                @endforeach
            </div>

            <!-- Actions -->
            <div class="mt-4 space-y-2">
                @foreach($actions as $action)
                    <a href="{{ $action['url'] }}" class="block w-full text-center py-2 px-4 rounded-lg text-sm font-medium transition-colors {{ $action['class'] }}">
                        {{ $action['label'] }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translate3d(0, 20px, 0);
            }
            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const reminder = document.getElementById('security-reminder');
            const closeBtn = document.getElementById('close-reminder');
            // Use session ID to ensure reminder resets on new login/account switch
            const storageKey = 'security_reminder_dismissed_{{ session()->getId() }}';
            
            // Check if dismissed in this specific session
            if (!sessionStorage.getItem(storageKey)) {
                reminder.style.display = 'block';
            }

            function dismissReminder() {
                reminder.style.opacity = '0';
                reminder.style.transform = 'translate3d(0, 20px, 0)';
                setTimeout(() => {
                    reminder.remove();
                }, 300);
                sessionStorage.setItem(storageKey, 'true');
            }

            if (closeBtn) {
                closeBtn.addEventListener('click', function() {
                    dismissReminder();
                });
            }
        });
    </script>
@endif
