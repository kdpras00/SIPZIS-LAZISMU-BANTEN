@php
    $user = auth()->user();
    $showReminder = false;
    $messages = [];
    $actions = [];

    if ($user) {
        // Check 2FA
        if (!$user->two_factor_enabled) {
            $messages[] = 'Aktifkan 2FA untuk keamanan ekstra akun Anda.';
            $actions[] = [
                'label' => 'Aktifkan 2FA',
                'url' => route('dashboard.two-factor.setup'),
                'class' => 'bg-white text-orange-700 border border-orange-200 hover:bg-orange-50'
            ];
        }

        // Check Profile Completeness (only for Muzakki)
        if ($user->hasRole('muzakki') && $user->muzakki) {
            $completeness = $user->muzakki->profile_completeness;
            if ($completeness < 100) {
                $messages[] = 'Lengkapi profil Anda (' . $completeness . '%).';
                $actions[] = [
                    'label' => 'Lengkapi Profil',
                    'url' => route('profile.edit'),
                    'class' => 'bg-orange-600 text-white hover:bg-orange-700 border border-transparent'
                ];
            }
        }

        if (!empty($messages)) {
            $showReminder = true;
        }
    }
@endphp

@if ($showReminder)
    <div id="security-reminder" class="mb-6 bg-[#fff7ed] border border-[#ffedd5] rounded-2xl p-4 md:p-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 shadow-sm" style="display: none;">
        
        <div class="flex items-start gap-3.5 mb-4 md:mb-0">
            <div class="p-2.5 bg-orange-100 rounded-xl text-orange-600 flex-shrink-0 mt-0.5">
                <i class="bi bi-shield-exclamation text-xl"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-orange-900 mb-1.5">Perhatian: Tindakan Diperlukan</h3>
                <div class="text-sm text-orange-800 space-y-1">
                    @foreach($messages as $message)
                        <div class="flex items-center gap-2">
                            <i class="bi bi-check-circle-fill text-orange-400 text-[10px]"></i> {{ $message }}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        <div class="flex flex-wrap items-center gap-2.5 w-full md:w-auto mt-2 md:mt-0 pt-3 md:pt-0 border-t border-orange-200/50 md:border-t-0">
            @foreach($actions as $action)
                <a href="{{ $action['url'] }}" class="inline-flex items-center justify-center px-4 py-2.5 text-xs font-semibold rounded-lg transition-colors {{ $action['class'] }}">
                    {{ $action['label'] }}
                </a>
            @endforeach
            <button id="close-reminder" type="button" class="inline-flex items-center justify-center p-2 text-orange-400 hover:text-orange-600 hover:bg-orange-100 rounded-lg transition-colors ml-auto md:ml-2" title="Tutup">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const reminder = document.getElementById('security-reminder');
            const closeBtn = document.getElementById('close-reminder');
            // Use session ID to ensure reminder resets on new login/account switch
            const storageKey = 'security_reminder_dismissed_{{ session()->getId() }}';
            
            // Check if dismissed in this specific session
            if (!sessionStorage.getItem(storageKey)) {
                reminder.style.display = 'flex';
            }

            if (closeBtn) {
                closeBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    // Fade out animation
                    reminder.style.transition = 'opacity 0.3s ease';
                    reminder.style.opacity = '0';
                    setTimeout(() => {
                        reminder.style.display = 'none';
                        sessionStorage.setItem(storageKey, 'true');
                    }, 300);
                });
            }
        });
    </script>
@endif
