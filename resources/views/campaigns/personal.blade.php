@extends('layouts.app')

@section('page-title', 'Profil Campaigner - ' . $muzakki->name)

@section('content')
<div class="py-6 px-4 max-w-6xl mx-auto space-y-6">

    
    <div class="bg-white rounded-2xl border border-[#f0ece6] p-6 shadow-sm">
        <div class="flex flex-col md:flex-row items-center md:items-start justify-between gap-6">
            <div class="flex flex-col md:flex-row items-center md:items-start gap-4 text-center md:text-left">
                @if($muzakki->profile_photo)
                    <img src="{{ asset('storage/' . $muzakki->profile_photo) }}"
                         alt="{{ $muzakki->name }}"
                         class="w-20 h-20 rounded-full object-cover border-2 border-[#f0ece6] shadow-sm">
                @else
                    <div class="w-20 h-20 rounded-full bg-[#c2410c] text-white font-bold text-2xl flex items-center justify-center shadow-sm">
                        {{ strtoupper(substr($muzakki->name, 0, 1)) }}
                    </div>
                @endif

                <div class="space-y-1">
                    <div class="flex items-center justify-center md:justify-start gap-2">
                        <h1 class="text-xl font-bold text-[#1c0f0a] m-0">{{ $muzakki->name }}</h1>
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <i class="bi bi-patch-check-fill text-emerald-600"></i> Verified Campaigner
                        </span>
                    </div>
                    <p class="text-xs text-[#8b7e74] m-0 flex items-center justify-center md:justify-start gap-1">
                        <i class="bi bi-envelope"></i> {{ $muzakki->email }}
                    </p>
                    @if($muzakki->bio)
                        <p class="text-xs text-[#1c0f0a] m-0 pt-1 max-w-xl leading-relaxed">
                            {{ Str::limit(strip_tags($muzakki->bio), 150) }}
                        </p>
                    @endif
                </div>
            </div>

            
            <div class="flex items-center gap-3 flex-shrink-0">
                <button type="button" 
                        onclick="copyProfileLink('{{ url()->current() }}')"
                        class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-[#1c0f0a] bg-[#faf8f5] border border-[#e8e0d6] rounded-xl hover:bg-[#f0ece6] transition-all shadow-2xs">
                    <i class="bi bi-share text-sm text-[#c2410c]"></i> Bagikan Profil
                </button>
            </div>
        </div>
    </div>

    
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl border border-[#f0ece6] p-5 shadow-sm text-center">
            <div class="w-10 h-10 rounded-xl bg-rose-50 border border-rose-100 flex items-center justify-center mx-auto mb-2 text-rose-600">
                <i class="bi bi-heart-fill text-lg"></i>
            </div>
            <p class="text-xl font-bold text-[#1c0f0a] m-0 tabular-nums">
                {{ number_format($campaigns->sum(function($c) { return $c->donations_count ?? 0; })) }}
            </p>
            <span class="text-xs text-[#8b7e74]">Total Donatur</span>
        </div>

        <div class="bg-white rounded-2xl border border-[#f0ece6] p-5 shadow-sm text-center">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center mx-auto mb-2 text-emerald-600">
                <i class="bi bi-wallet2 text-lg"></i>
            </div>
            <p class="text-xl font-bold text-[#1c0f0a] m-0 tabular-nums">
                Rp {{ number_format($campaigns->sum('collected_amount'), 0, ',', '.') }}
            </p>
            <span class="text-xs text-[#8b7e74]">Total Dana Terkumpul</span>
        </div>

        <div class="bg-white rounded-2xl border border-[#f0ece6] p-5 shadow-sm text-center">
            <div class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center mx-auto mb-2 text-amber-600">
                <i class="bi bi-trophy-fill text-lg"></i>
            </div>
            <p class="text-xl font-bold text-[#1c0f0a] m-0 tabular-nums">
                {{ $campaigns->where('status', 'completed')->count() }}
            </p>
            <span class="text-xs text-[#8b7e74]">Campaign Selesai</span>
        </div>
    </div>

    
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-base font-bold text-[#1c0f0a] m-0">Daftar Campaign Aktif</h2>
            <span class="text-xs text-[#8b7e74]">{{ $campaigns->total() }} Campaign</span>
        </div>

        @if($campaigns->isEmpty())
        <div class="bg-white rounded-2xl border border-[#f0ece6] p-12 text-center shadow-sm">
            <div class="w-14 h-14 rounded-2xl bg-[#faf8f5] border border-[#f0ece6] flex items-center justify-center mx-auto mb-3 text-[#8b7e74]">
                <i class="bi bi-inbox text-2xl"></i>
            </div>
            <h3 class="text-sm font-semibold text-[#1c0f0a] m-0 mb-1">Belum Ada Campaign Aktif</h3>
            <p class="text-xs text-[#8b7e74] m-0">Campaigner ini belum mempublikasikan kampanye galang dana.</p>
        </div>
        @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($campaigns as $campaign)
            <div class="bg-white rounded-2xl border border-[#f0ece6] overflow-hidden shadow-sm hover:shadow-md transition-all flex flex-col h-full">
                
                <div class="relative h-44 bg-gray-200 overflow-hidden flex items-center justify-center">
                    @if($campaign->image_url)
                    <img src="{{ $campaign->image_url }}"
                         alt="{{ $campaign->title }}"
                         class="absolute inset-0 w-full h-full object-cover z-0">
                    @else
                    <svg class="w-12 h-12 text-gray-400 z-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    @endif
                    <span class="absolute top-3 left-3 px-2.5 py-1 rounded-lg text-[10px] font-semibold bg-white/90 backdrop-blur-sm text-[#1c0f0a] shadow-xs uppercase tracking-wider z-10">
                        {{ ucfirst($campaign->program_category ?? 'Zakat') }}
                    </span>
                </div>

                
                <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                    <div class="space-y-2">
                        <h3 class="text-sm font-bold text-[#1c0f0a] line-clamp-2 leading-snug m-0">
                            {{ $campaign->title }}
                        </h3>
                        <p class="text-xs text-[#8b7e74] line-clamp-2 m-0 leading-relaxed">
                            {{ Str::limit(strip_tags($campaign->description), 90) }}
                        </p>
                    </div>

                    <div class="space-y-3 pt-2 border-t border-[#f0ece6]">
                        
                        <div>
                            <div class="flex justify-between text-xs mb-1">
                                <span class="text-[#8b7e74]">Terkumpul</span>
                                <span class="font-bold text-[#c2410c]">Rp {{ number_format($campaign->collected_amount ?? 0, 0, ',', '.') }}</span>
                            </div>
                            <div class="w-full h-2 rounded-full bg-[#f0ece6] overflow-hidden">
                                <div class="h-full rounded-full bg-[#c2410c] transition-all duration-500"
                                     style="width: {{ min(100, $campaign->progress_percentage ?? 0) }}%"></div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-1">
                            <span class="text-[11px] text-[#8b7e74] flex items-center gap-1">
                                <i class="bi bi-people"></i> {{ $campaign->donations_count ?? 0 }} Donatur
                            </span>
                            <a href="{{ route('campaigns.show', ['category' => $campaign->program_category ?? 'infaq', 'campaign' => $campaign->slug ?? $campaign->id]) }}"
                               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-[#c2410c] hover:bg-[#9a3412] transition-colors shadow-2xs">
                                Donasi Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="flex justify-center pt-4">
            {{ $campaigns->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function copyProfileLink(url) {
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(url).then(() => showToast('Link profil berhasil disalin!'));
        } else {
            const textArea = document.createElement('textarea');
            textArea.value = url;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            showToast('Link profil berhasil disalin!');
        }
    }

    function showToast(msg) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: msg,
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true
            });
        } else {
            alert(msg);
        }
    }
</script>
@endpush
@endsection