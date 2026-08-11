<tr class="hover:bg-[#faf8f5]/60 transition-colors duration-150">
    <td class="px-5 py-4 whitespace-nowrap">
        <div class="flex items-center">
            @if($program->image_url)
                <img src="{{ $program->image_url }}" 
                     alt="{{ $program->name }}"
                     class="h-14 w-14 rounded-xl object-cover mr-3.5 flex-shrink-0 border border-[#f0ece6]">
            @else
                <div class="h-14 w-14 rounded-xl mr-3.5 flex-shrink-0 border border-[#f0ece6] bg-gray-200 flex items-center justify-center">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            @endif
            <div class="min-w-0">
                <div class="text-xs font-bold truncate max-w-[220px]" style="color: #1c0f0a;">
                    {{ $program->name }}
                </div>
                <div class="text-[11px] truncate max-w-[220px] mt-0.5" style="color: #8b7e74;">
                    {{ Str::limit($program->description, 50) }}
                </div>
            </div>
        </div>
    </td>
    <td class="px-5 py-4 whitespace-nowrap">
        <span class="inline-flex items-center text-[#c2410c] text-xs font-semibold">
            {{ $categoryName }}
        </span>
    </td>
    <td class="px-5 py-4 whitespace-nowrap text-center text-xs font-bold" style="color: #1c0f0a;">
        {{ $program->formatted_total_target }}
    </td>
    <td class="px-5 py-4 whitespace-nowrap text-center text-xs font-bold text-[#c2410c]">
        {{ $program->formatted_total_collected }}
    </td>
    <td class="px-5 py-4 whitespace-nowrap">
        <div class="flex items-center justify-center gap-2">
            <span class="text-xs font-bold w-10 text-right" style="color: #1c0f0a;">
                {{ number_format($program->progress_percentage, 1) }}%
            </span>
            <div class="w-24 bg-[#f0ece6] rounded-full h-2 overflow-hidden">
                <div class="h-2 rounded-full transition-all duration-300" 
                     style="background: #c2410c; width: {{ min($program->progress_percentage, 100) }}%"></div>
            </div>
        </div>
    </td>
    <td class="px-5 py-4 whitespace-nowrap text-center">
        @if($program->status == 'active')
            <span class="inline-flex items-center text-[#c2410c] text-xs font-semibold">
                Aktif
            </span>
        @else
            <span class="inline-flex items-center text-[#8b7e74] text-xs font-semibold">
                Tidak Aktif
            </span>
        @endif
    </td>
    <td class="px-5 py-4 whitespace-nowrap text-center">
        <div class="flex items-center justify-start xl:justify-center gap-1.5">
            <a href="{{ route('admin.programs.edit', $program) }}" 
               class="inline-flex items-center justify-center h-8 px-2.5 rounded-lg text-xs font-medium transition-colors"
               style="background: #f0ece6; color: #1c0f0a;"
               title="Edit Program">
                <i class="bi bi-pencil mr-1 text-[11px]"></i> Edit
            </a>
            <form action="{{ route('admin.programs.destroy', $program) }}" 
                  method="POST" 
                  class="inline"
                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus program ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="inline-flex items-center justify-center h-8 px-2.5 rounded-lg text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 transition-colors"
                        title="Hapus Program">
                    <i class="bi bi-trash mr-1 text-[11px]"></i> Hapus
                </button>
            </form>
        </div>
    </td>
</tr>
