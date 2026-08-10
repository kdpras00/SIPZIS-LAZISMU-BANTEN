<tr class="hover:bg-[#faf8f5]/60 transition-colors duration-150">
    <td class="px-5 py-4 whitespace-nowrap">
        <div class="flex items-center">
            <img src="{{ $program->image_url }}" 
                 alt="{{ $program->name }}"
                 onerror="this.src='{{ asset('img/masjidbanten.png') }}';"
                 class="h-14 w-14 rounded-xl object-cover mr-3.5 flex-shrink-0 border border-[#f0ece6]">
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
        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold" style="background: #fff7ed; color: #c2410c; border: 1px solid #ffedd5;">
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
            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold" style="background: #fff7ed; color: #c2410c; border: 1px solid #ffedd5;">
                Aktif
            </span>
        @else
            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold" style="background: #f0ece6; color: #8b7e74;">
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
