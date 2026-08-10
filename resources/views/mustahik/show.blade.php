@extends('layouts.app')

@section('page-title', 'Detail Mustahik - ' . $mustahik->name)

@section('content')
<div class="px-4 sm:px-6 py-5 w-full mx-auto" style="max-width: 1280px;">
    
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-6">
        <div>
            <h2 class="text-xl font-bold mb-1" style="color: #1c0f0a;">Detail Mustahik</h2>
            <p class="text-sm" style="color: #8b7e74;">{{ $mustahik->name }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('mustahik.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-medium transition-colors duration-200"
                style="background: #f0ece6; color: #1c0f0a;">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('mustahik.edit', $mustahik) }}"
                class="inline-flex items-center px-4 py-2 text-white font-medium rounded-xl transition-colors duration-200 text-xs shadow-xs" style="background: #c2410c;">
                <i class="bi bi-pencil-fill mr-1.5"></i> Edit Data
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            
            <div class="rounded-2xl overflow-hidden" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04); border: 1px solid #f0ece6;">
                <div class="px-6 py-4 border-b border-[#f0ece6]">
                    <h5 class="text-sm font-bold text-[#1c0f0a] mb-0 flex items-center">
                        <i class="bi bi-person-fill mr-2" style="color: #c2410c;"></i> Informasi Personal
                    </h5>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                        <div>
                            <label class="block text-[#8b7e74] mb-1">Nama Lengkap</label>
                            <div class="font-bold text-[#1c0f0a] text-sm">{{ $mustahik->name }}</div>
                        </div>

                        <div>
                            <label class="block text-[#8b7e74] mb-1">NIK</label>
                            <div class="font-medium text-[#1c0f0a]">{{ $mustahik->nik ?: '-' }}</div>
                        </div>

                        <div>
                            <label class="block text-[#8b7e74] mb-1">Jenis Kelamin</label>
                            <div class="font-medium text-[#1c0f0a]">
                                @if ($mustahik->gender == 'male')
                                    <i class="bi bi-gender-male text-blue-600 mr-1"></i> Laki-laki
                                @elseif($mustahik->gender == 'female')
                                    <i class="bi bi-gender-female text-pink-600 mr-1"></i> Perempuan
                                @else
                                    -
                                @endif
                            </div>
                        </div>

                        <div>
                            <label class="block text-[#8b7e74] mb-1">Tanggal Lahir</label>
                            <div class="font-medium text-[#1c0f0a]">
                                @if ($mustahik->date_of_birth)
                                    {{ $mustahik->date_of_birth->format('d M Y') }}
                                    @if ($mustahik->age)
                                        <span class="ml-2 px-2 py-0.5 text-[11px] font-medium rounded-full bg-gray-100 text-gray-800">
                                            {{ $mustahik->age }} tahun
                                        </span>
                                    @endif
                                @else
                                    -
                                @endif
                            </div>
                        </div>

                        <div>
                            <label class="block text-[#8b7e74] mb-1">Nomor Telepon</label>
                            <div class="font-medium text-[#1c0f0a]">{{ $mustahik->phone ?: '-' }}</div>
                        </div>

                        <div>
                            <label class="block text-[#8b7e74] mb-1">Status Verifikasi</label>
                            <div>
                                @switch($mustahik->verification_status)
                                    @case('pending')
                                        <span class="px-2.5 py-0.5 text-[11px] font-semibold rounded-full bg-amber-50 text-amber-800">Menunggu Verifikasi</span>
                                    @break

                                    @case('verified')
                                        <span class="px-2.5 py-0.5 text-[11px] font-semibold rounded-full bg-[#fff7ed] text-[#c2410c]">Terverifikasi</span>
                                    @break

                                    @case('rejected')
                                        <span class="px-2.5 py-0.5 text-[11px] font-semibold rounded-full bg-red-50 text-red-700">Ditolak</span>
                                    @break

                                    @default
                                        <span class="px-2.5 py-0.5 text-[11px] font-semibold rounded-full bg-gray-100 text-gray-800">{{ $mustahik->verification_status }}</span>
                                @endswitch
                            </div>
                        </div>

                        <div>
                            <label class="block text-[#8b7e74] mb-1">Status Aktif</label>
                            <div>
                                @if ($mustahik->is_active)
                                    <span class="px-2.5 py-0.5 text-[11px] font-semibold rounded-full bg-[#fff7ed] text-[#c2410c]">Aktif</span>
                                @else
                                    <span class="px-2.5 py-0.5 text-[11px] font-semibold rounded-full bg-gray-100 text-gray-800">Tidak Aktif</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="rounded-2xl overflow-hidden" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04); border: 1px solid #f0ece6;">
                <div class="px-6 py-4 border-b border-[#f0ece6]">
                    <h5 class="text-sm font-bold text-[#1c0f0a] mb-0 flex items-center">
                        <i class="bi bi-geo-alt-fill mr-2" style="color: #c2410c;"></i> Informasi Alamat
                    </h5>
                </div>
                <div class="p-6 text-xs">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[#8b7e74] mb-1">Alamat Lengkap</label>
                            <div class="font-medium text-[#1c0f0a] leading-relaxed">{{ $mustahik->address ?: '-' }}</div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[#8b7e74] mb-1">Kota/Kabupaten</label>
                                <div class="font-medium text-[#1c0f0a]">{{ $mustahik->city ?: '-' }}</div>
                            </div>

                            <div>
                                <label class="block text-[#8b7e74] mb-1">Provinsi</label>
                                <div class="font-medium text-[#1c0f0a]">{{ $mustahik->province ?: '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="rounded-2xl overflow-hidden" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04); border: 1px solid #f0ece6;">
                <div class="px-6 py-4 border-b border-[#f0ece6]">
                    <h5 class="text-sm font-bold text-[#1c0f0a] mb-0 flex items-center">
                        <i class="bi bi-tags-fill mr-2" style="color: #c2410c;"></i> Kategori Mustahik (Asnaf)
                    </h5>
                </div>
                <div class="p-6 text-xs">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[#8b7e74] mb-1">Kategori</label>
                            <div>
                                <span class="px-2.5 py-0.5 text-[11px] font-semibold rounded-full bg-[#fff7ed] text-[#c2410c]">{{ ucfirst(str_replace('_', ' ', $mustahik->category)) }}</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[#8b7e74] mb-1">Jumlah Anggota Keluarga</label>
                            <div class="font-medium text-[#1c0f0a]">{{ $mustahik->family_members }} orang</div>
                        </div>

                        <div>
                            <label class="block text-[#8b7e74] mb-1">Status Keluarga</label>
                            <div class="font-medium text-[#1c0f0a]">
                                @switch($mustahik->family_status)
                                    @case('single') Lajang @break
                                    @case('married') Menikah @break
                                    @case('divorced') Cerai @break
                                    @case('widow/widower') Janda/Duda @break
                                    @default -
                                @endswitch
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-[#8b7e74] mb-1">Deskripsi Kondisi</label>
                            <div class="font-medium text-[#1c0f0a] leading-relaxed">{{ $mustahik->category_description ?: '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1 space-y-6">
            
            <div class="rounded-2xl overflow-hidden" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04); border: 1px solid #f0ece6;">
                <div class="px-6 py-4 border-b border-[#f0ece6]">
                    <h6 class="text-sm font-bold text-[#1c0f0a] mb-0 flex items-center">
                        <i class="bi bi-bar-chart-fill mr-2" style="color: #c2410c;"></i> Statistik Penerimaan Zakat
                    </h6>
                </div>
                <div class="p-6 text-center">
                    <div class="w-14 h-14 rounded-2xl mx-auto flex items-center justify-center mb-4" style="background: #fff7ed; color: #c2410c;">
                        <i class="bi bi-wallet2 text-2xl"></i>
                    </div>

                    <div class="mb-4">
                        <h3 class="text-2xl font-bold text-[#1c0f0a] mb-1">{{ $stats['distribution_count'] }}</h3>
                        <p class="text-xs text-[#8b7e74]">Total Distribusi</p>
                    </div>

                    <div class="mb-4">
                        <h3 class="text-2xl font-bold mb-1" style="color: #c2410c;">Rp {{ number_format($stats['total_received'], 0, ',', '.') }}</h3>
                        <p class="text-xs text-[#8b7e74]">Total Zakat Diterima</p>
                    </div>

                    <div>
                        <p class="text-xs text-[#8b7e74] mb-1">Terakhir Menerima</p>
                        <p class="font-medium text-[#1c0f0a] text-xs">
                            @if ($stats['last_distribution'])
                                {{ $stats['last_distribution']->distribution_date->format('d M Y') }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            
            <div class="rounded-2xl overflow-hidden" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04); border: 1px solid #f0ece6;">
                <div class="px-6 py-4 border-b border-[#f0ece6]">
                    <h6 class="text-sm font-bold text-[#1c0f0a] mb-0 flex items-center">
                        <i class="bi bi-list-check mr-2" style="color: #c2410c;"></i> Distribusi Terbaru
                    </h6>
                </div>
                <div class="p-6 text-xs">
                    @if ($recentDistributions->count() > 0)
                        <div class="space-y-3">
                            @foreach ($recentDistributions as $distribution)
                                <div class="flex justify-between items-center py-2 border-b border-[#f0ece6] last:border-0">
                                    <div>
                                        <div class="font-bold text-[#1c0f0a]">Rp {{ number_format($distribution->amount, 0, ',', '.') }}</div>
                                        <div class="text-[11px] text-[#8b7e74]">{{ $distribution->distribution_date->format('d M Y') }}</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-[11px] text-[#8b7e74]">{{ $distribution->distributedBy->name ?? 'System' }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6">
                            <i class="bi bi-inbox text-3xl text-[#8b7e74] block mb-2"></i>
                            <p class="text-xs text-[#8b7e74]">Belum ada distribusi</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
