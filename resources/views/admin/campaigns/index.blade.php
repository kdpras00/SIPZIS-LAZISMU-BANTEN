@extends('layouts.app')

@section('page-title', 'Kelola Campaign')

@section('content')
<div class="px-4 sm:px-6 py-5 w-full mx-auto" style="max-width: 1280px;">
    
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-6">
        <div>
            <h2 class="text-xl font-bold mb-1" style="color: #1c0f0a;">Kelola Campaign</h2>
            <p class="text-sm" style="color: #8b7e74;">Daftar semua campaign yang tersedia di sistem</p>
        </div>
        <a href="{{ route('admin.campaigns.create') }}" 
           class="inline-flex items-center px-4 py-2 text-white font-medium rounded-xl transition-colors duration-200 text-xs shadow-xs" style="background: #c2410c;">
            <i class="bi bi-plus-circle-fill mr-1.5"></i> Tambah Campaign
        </a>
    </div>

    
    <div class="rounded-2xl overflow-hidden" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04); border: 1px solid #f0ece6;">
        <div class="overflow-x-auto">
            <table id="table-campaigns" class="min-w-full divide-y divide-[#f0ece6]">
                <thead style="background: #faf8f5;">
                    <tr>
                        <th scope="col" class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">
                            Campaign
                        </th>
                        <th scope="col" class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">
                            Kategori
                        </th>
                        <th scope="col" class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">
                            Target
                        </th>
                        <th scope="col" class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">
                            Terkumpul
                        </th>
                        <th scope="col" class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">
                            Progress
                        </th>
                        <th scope="col" class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">
                            Sisa Hari
                        </th>
                        <th scope="col" class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">
                            Status
                        </th>
                        <th scope="col" class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-[#f0ece6]">
                    @forelse($campaigns as $campaign)
                    <tr class="hover:bg-[#faf8f5]/60 transition-colors duration-150">
                        <td class="px-5 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <img src="{{ $campaign->image_url }}"
                                     alt="{{ $campaign->title }}"
                                     class="h-14 w-14 rounded-xl object-cover mr-3.5 flex-shrink-0 border border-[#f0ece6]">
                                <div class="min-w-0">
                                    <div class="text-xs font-bold truncate max-w-[220px]" style="color: #1c0f0a;">
                                        {{ $campaign->title }}
                                    </div>
                                    <div class="text-[11px] truncate max-w-[220px] mt-0.5" style="color: #8b7e74;">
                                        {{ Str::limit($campaign->description, 50) }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold" style="background: #fff7ed; color: #c2410c; border: 1px solid #ffedd5;">
                                {{ ucfirst(str_replace('-', ' ', $campaign->program_category)) }}
                            </span>
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap text-center text-xs font-bold" style="color: #1c0f0a;">
                            Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap text-center text-xs font-bold text-[#c2410c]">
                            Rp {{ number_format($campaign->display_collected_amount ?? $campaign->collected_amount, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap">
                            <div class="flex items-center justify-start xl:justify-center gap-1.5">
                                <span class="text-xs font-bold w-10 text-right" style="color: #1c0f0a;">
                                    {{ number_format($campaign->progress_percentage, 1) }}%
                                </span>
                                <div class="w-24 bg-[#f0ece6] rounded-full h-2 overflow-hidden">
                                    <div class="h-2 rounded-full transition-all duration-300" 
                                         style="background: #c2410c; width: {{ min($campaign->progress_percentage, 100) }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap text-center">
                            @if($campaign->end_date)
                                @if($campaign->remaining_days > 0)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium" style="background: #f0ece6; color: #1c0f0a;">
                                        <i class="bi bi-clock mr-1 text-[11px]" style="color: #8b7e74;"></i> {{ $campaign->remaining_days }} hari
                                    </span>
                                @elseif($campaign->remaining_days == 0)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold" style="background: #fff7ed; color: #c2410c;">
                                        Hari terakhir
                                    </span>
                                @else
                                    @if($campaign->status == 'completed')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold" style="background: #ecfdf5; color: #059669;">
                                            Selesai
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold" style="background: #fef2f2; color: #dc2626;">
                                            Waktu Habis
                                        </span>
                                    @endif
                                @endif
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium" style="background: #f0ece6; color: #8b7e74;">
                                    Tanpa batas
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap text-center">
                            @if($campaign->status == 'published')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold" style="background: #fff7ed; color: #c2410c; border: 1px solid #ffedd5;">
                                    Published
                                </span>
                            @elseif($campaign->status == 'draft')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold" style="background: #f0ece6; color: #1c0f0a;">
                                    Draft
                                </span>
                            @elseif($campaign->status == 'completed')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold" style="background: #ecfdf5; color: #059669;">
                                    Completed
                                </span>
                            @elseif($campaign->status == 'cancelled')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold" style="background: #fef2f2; color: #dc2626;">
                                    Cancelled
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="{{ route('admin.campaigns.edit', $campaign) }}" 
                                   class="inline-flex items-center justify-center h-8 px-2.5 rounded-lg text-xs font-medium transition-colors"
                                   style="background: #f0ece6; color: #1c0f0a;"
                                   title="Edit Campaign">
                                    <i class="bi bi-pencil mr-1 text-[11px]"></i> Edit
                                </a>
                                <form action="{{ route('admin.campaigns.destroy', $campaign) }}" 
                                      method="POST"
                                      class="inline"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus campaign ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center justify-center h-8 px-2.5 rounded-lg text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 transition-colors"
                                            title="Hapus Campaign">
                                        <i class="bi bi-trash mr-1 text-[11px]"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <i class="bi bi-megaphone text-4xl mb-2" style="color: #d1cbc4;"></i>
                                <p class="text-sm font-semibold mb-0" style="color: #1c0f0a;">Belum ada campaign yang tersedia</p>
                                <p class="text-xs mt-1" style="color: #8b7e74;">Mulai dengan membuat campaign baru</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (window.SipzisTable) {
        window.SipzisTable.initTable('#table-campaigns');
    }
});
</script>
@endpush
