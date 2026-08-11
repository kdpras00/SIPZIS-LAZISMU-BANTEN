@extends('layouts.app')

@section('page-title', 'Manajemen Berita')

@section('content')
<div class="px-4 sm:px-6 py-5 w-full mx-auto" style="max-width: 1280px;">
    
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-6">
        <div>
            <h2 class="text-xl font-bold mb-1" style="color: #1c0f0a;">Manajemen Berita</h2>
            <p class="text-sm" style="color: #8b7e74;">Kelola publikasi berita Lazismu Banten</p>
        </div>
        <a href="{{ route('admin.news.create') }}" class="inline-flex items-center px-4 py-2 text-white font-medium rounded-xl transition-colors duration-200 text-xs shadow-xs" style="background: #c2410c;">
            <i class="bi bi-plus-circle-fill mr-1.5"></i> Tambah Berita
        </a>
    </div>

    
    <div class="rounded-2xl overflow-hidden" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04); border: 1px solid #f0ece6;">
        <div class="overflow-x-auto">
            <table id="table-news" class="min-w-full divide-y divide-[#f0ece6]">
                <thead style="background: #faf8f5;">
                    <tr>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Judul</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Penulis</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Status</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Tanggal</th>
                        <th class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-[#f0ece6]">
                    @forelse($news as $article)
                    <tr class="hover:bg-[#faf8f5]/60 transition-colors duration-150">
                        <td class="px-5 py-4">
                            <div class="flex items-center">
                                @if($article->image)
                                @php
                                $rawImage = trim($article->image ?? '');
                                $isFullUrl = filter_var($rawImage, FILTER_VALIDATE_URL);
                                $imageUrl = $isFullUrl ? $rawImage : Storage::url($article->image);
                                @endphp
                                <img src="{{ $imageUrl }}" alt="News Image" class="w-12 h-12 rounded-xl object-cover mr-3.5 border border-[#f0ece6]">
                                @else
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center mr-3.5 border border-[#f0ece6]" style="background: #faf8f5;">
                                    <i class="bi bi-image text-xl" style="color: #8b7e74;"></i>
                                </div>
                                @endif
                                <div class="min-w-0">
                                    <div class="text-xs font-bold truncate max-w-[280px]" style="color: #1c0f0a;">{{ Str::limit($article->title, 55) }}</div>
                                    <div class="text-[11px] truncate max-w-[280px] mt-0.5" style="color: #8b7e74;">{{ Str::limit($article->excerpt, 75) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap text-xs font-medium" style="color: #1c0f0a;">
                            {{ $article->author->name }}
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap">
                            <form action="{{ route('admin.news.toggle-publish', $article) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="inline-flex items-center text-xs font-semibold transition-colors cursor-pointer"
                                    style="{{ $article->is_published ? 'color: #c2410c;' : 'color: #1c0f0a;' }}">
                                    <i class="bi bi-circle-fill text-[8px] mr-1.5" style="{{ $article->is_published ? 'color: #c2410c;' : 'color: #8b7e74;' }}"></i>
                                    {{ $article->is_published ? 'Published' : 'Draft' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap text-xs" style="color: #8b7e74;">
                            {{ $article->formatted_date }}
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap text-center text-xs">
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="{{ route('admin.news.show', $article) }}" class="inline-flex items-center justify-center h-8 px-2.5 rounded-lg text-xs font-medium transition-colors" style="background: #f0ece6; color: #1c0f0a;" title="Lihat Berita">
                                    <i class="bi bi-eye text-xs"></i>
                                </a>
                                <a href="{{ route('admin.news.edit', $article) }}" class="inline-flex items-center justify-center h-8 px-2.5 rounded-lg text-xs font-medium transition-colors" style="background: #f0ece6; color: #1c0f0a;" title="Edit Berita">
                                    <i class="bi bi-pencil text-xs"></i>
                                </a>
                                <form action="{{ route('admin.news.destroy', $article) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus berita ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center h-8 px-2.5 rounded-lg text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 transition-colors" title="Hapus Berita">
                                        <i class="bi bi-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <i class="bi bi-newspaper text-4xl mb-2" style="color: #d1cbc4;"></i>
                                <p class="text-sm font-semibold mb-0" style="color: #1c0f0a;">Belum ada berita yang tersedia</p>
                                <p class="text-xs mt-1" style="color: #8b7e74;">Mulai dengan membuat berita baru</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($news->hasPages())
        <div class="px-5 py-4 border-t border-[#f0ece6]" style="background: #fff;">
            {{ $news->links() }}
        </div>
        @endif
    </div>
</div>
@endsection