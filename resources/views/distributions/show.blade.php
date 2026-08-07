@extends('layouts.app')

@section('page-title', 'Detail Distribusi - ' . $distribution->distribution_code)

@section('content')
@php
    $statusBadge = $distribution->is_received
        ? ['text' => 'Sudah Diterima', 'class' => 'bg-orange-100 text-orange-700']
        : ['text' => 'Belum Diterima', 'class' => 'bg-amber-100 text-amber-700'];

    $typeLabels = [
        'cash' => ['Tunai', 'bg-orange-100 text-orange-700'],
        'goods' => ['Barang', 'bg-sky-100 text-sky-700'],
        'voucher' => ['Voucher', 'bg-amber-100 text-amber-700'],
        'service' => ['Layanan', 'bg-indigo-100 text-indigo-700'],
    ];
    $typeLabel = $typeLabels[$distribution->distribution_type] ?? [ucwords($distribution->distribution_type), 'bg-gray-100 text-gray-700'];
@endphp

<div class="px-4 sm:px-6 py-5 w-full mx-auto" style="max-width: 1280px;">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold mb-1" style="color: #1c0f0a;">Detail Distribusi</h2>
            <p class="text-sm font-mono" style="color: #8b7e74;">#{{ $distribution->distribution_code }}</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('distributions.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-medium transition-colors duration-200"
                style="background: #f0ece6; color: #1c0f0a;">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('distributions.edit', $distribution) }}"
                class="inline-flex items-center px-4 py-2 font-medium rounded-xl transition-all duration-200 text-xs hover:bg-gray-50" style="border: 1px solid #e8e0d6; color: #1c0f0a; background: #fff;">
                <i class="bi bi-pencil-fill mr-1.5" style="color: #c2410c;"></i> Edit
            </a>
            <a href="{{ route('distributions.receipt', $distribution) }}" target="_blank"
                class="inline-flex items-center px-4 py-2 text-white font-medium rounded-xl transition-colors duration-200 text-xs shadow-xs" style="background: #c2410c;">
                <i class="bi bi-receipt-cutoff mr-1.5"></i> Kwitansi
            </a>
            @if (! $distribution->is_received)
                <button type="button" data-open-mark-received
                        class="inline-flex items-center px-4 py-2 text-amber-800 bg-amber-100 hover:bg-amber-200 font-medium rounded-xl transition-colors duration-200 text-xs">
                    <i class="bi bi-check-circle-fill mr-1.5"></i> Tandai Diterima
                </button>
            @endif
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <section class="rounded-2xl border border-gray-100 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center justify-center rounded-full bg-orange-50 p-2 text-orange-600">
                            <i class="bi bi-hand-thumbs-up-fill text-lg"></i>
                        </span>
                        <h2 class="text-lg font-semibold text-gray-900">Informasi Distribusi</h2>
                    </div>
                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusBadge['class'] }}">
                        {{ $statusBadge['text'] }}
                    </span>
                </div>
                <div class="space-y-6 p-6">
                    <dl class="grid gap-6 sm:grid-cols-2">
                        <div class="rounded-2xl border border-gray-100 bg-gray-50 p-4">
                            <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Kode Distribusi</dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $distribution->distribution_code }}</dd>
                        </div>
                        <div class="rounded-2xl border border-gray-100 bg-gray-50 p-4">
                            <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Jenis Distribusi</dt>
                            <dd class="mt-2">
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $typeLabel[1] }}">
                                    {{ $typeLabel[0] }}
                                </span>
                            </dd>
                        </div>
                        <div class="rounded-2xl border border-gray-100 bg-white p-4 shadow-inner">
                            <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Jumlah</dt>
                            <dd class="mt-1 text-2xl font-bold text-orange-600">
                                Rp {{ number_format($distribution->amount, 0, ',', '.') }}
                            </dd>
                        </div>
                        <div class="rounded-2xl border border-gray-100 bg-white p-4 shadow-inner">
                            <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Tanggal Distribusi</dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900">
                                {{ $distribution->distribution_date->format('d F Y') }}
                            </dd>
                        </div>
                        <div class="rounded-2xl border border-gray-100 bg-white p-4">
                            <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Lokasi</dt>
                            <dd class="mt-1 text-base font-medium text-gray-900">{{ $distribution->location ?? '-' }}</dd>
                        </div>
                        <div class="rounded-2xl border border-gray-100 bg-white p-4">
                            <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Program</dt>
                            <dd class="mt-1">
                                @if ($distribution->program_name)
                                    <span class="inline-flex items-center rounded-full bg-cyan-50 px-3 py-1 text-xs font-semibold text-cyan-700">
                                        {{ $distribution->program_name }}
                                    </span>
                                @else
                                    <span class="text-sm text-gray-500">Tidak tercatat</span>
                                @endif
                            </dd>
                        </div>
                        <div class="rounded-2xl border border-gray-100 bg-white p-4">
                            <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Dicatat Oleh</dt>
                            <dd class="mt-1 text-base font-semibold text-gray-900">{{ $distribution->distributedBy->name ?? '-' }}</dd>
                        </div>
                        <div class="rounded-2xl border border-gray-100 bg-white p-4">
                            <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Tanggal Dicatat</dt>
                            <dd class="mt-1 text-base font-medium text-gray-900">{{ $distribution->created_at->format('d F Y H:i') }}</dd>
                        </div>
                        @if ($distribution->is_received)
                            <div class="rounded-2xl border border-gray-100 bg-orange-50 p-4">
                                <dt class="text-xs font-semibold uppercase tracking-wide text-orange-700">Tanggal Diterima</dt>
                                <dd class="mt-1 text-base font-semibold text-orange-900">
                                    {{ $distribution->received_date?->format('d F Y H:i') ?? 'Tidak tercatat' }}
                                </dd>
                            </div>
                        @endif
                        @if ($distribution->received_by_name)
                            <div class="rounded-2xl border border-gray-100 bg-orange-50 p-4">
                                <dt class="text-xs font-semibold uppercase tracking-wide text-orange-700">Diterima Oleh</dt>
                                <dd class="mt-1 text-base font-semibold text-orange-900">
                                    {{ $distribution->received_by_name }}
                                </dd>
                            </div>
                        @endif
                    </dl>

                    @if ($distribution->goods_description)
                        <div class="rounded-2xl border border-gray-100 bg-gray-50 p-4">
                            <h3 class="text-sm font-semibold text-gray-700">Deskripsi Barang / Layanan</h3>
                            <p class="mt-2 text-sm text-gray-600">{{ $distribution->goods_description }}</p>
                        </div>
                    @endif

                    @if ($distribution->notes)
                        <div class="rounded-2xl border border-gray-100 bg-white p-4">
                            <h3 class="text-sm font-semibold text-gray-700">Catatan</h3>
                            <p class="mt-2 text-sm text-gray-600">{{ $distribution->notes }}</p>
                        </div>
                    @endif

                    @if ($distribution->received_notes)
                        <div class="rounded-2xl border border-orange-200 bg-orange-50 p-4">
                            <h3 class="text-sm font-semibold text-orange-800">Catatan Penerimaan</h3>
                            <p class="mt-2 text-sm text-orange-900">{{ $distribution->received_notes }}</p>
                        </div>
                    @endif
                </div>
            </section>

            <section class="rounded-2xl border border-gray-100 bg-white shadow-sm">
                <div class="flex items-center gap-3 border-b border-gray-100 px-6 py-4">
                    <span class="inline-flex items-center justify-center rounded-full bg-indigo-50 p-2 text-indigo-600">
                        <i class="bi bi-clock-fill text-lg"></i>
                    </span>
                    <h2 class="text-lg font-semibold text-gray-900">Riwayat Aktivitas</h2>
                </div>
                <div class="p-6">
                    <ol class="relative border-l border-gray-200">
                        <li class="mb-10 ml-6">
                            <span class="absolute -left-3 flex h-6 w-6 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                                <i class="bi bi-journal-check text-sm"></i>
                            </span>
                            <h3 class="text-base font-semibold text-gray-900">Distribusi Dicatat</h3>
                            <p class="text-sm text-gray-600">
                                {{ $distribution->distributedBy->name ?? 'Sistem' }} mencatat distribusi ini.
                            </p>
                            <time class="text-xs text-gray-400">{{ $distribution->created_at->format('d F Y H:i') }}</time>
                        </li>

                        @if ($distribution->updated_at != $distribution->created_at)
                            <li class="mb-10 ml-6">
                                <span class="absolute -left-3 flex h-6 w-6 items-center justify-center rounded-full bg-cyan-100 text-cyan-600">
                                    <i class="bi bi-arrow-repeat text-sm"></i>
                                </span>
                                <h3 class="text-base font-semibold text-gray-900">Data Diperbarui</h3>
                                <p class="text-sm text-gray-600">Informasi distribusi diperbarui.</p>
                                <time class="text-xs text-gray-400">{{ $distribution->updated_at->format('d F Y H:i') }}</time>
                            </li>
                        @endif

                        @if ($distribution->is_received)
                            <li class="ml-6">
                                <span class="absolute -left-3 flex h-6 w-6 items-center justify-center rounded-full bg-orange-100 text-orange-600">
                                    <i class="bi bi-check-lg text-sm"></i>
                                </span>
                                <h3 class="text-base font-semibold text-gray-900">Distribusi Diterima</h3>
                                <p class="text-sm text-gray-600">
                                    Dikonfirmasi telah diterima {{ $distribution->received_by_name ? 'oleh ' . $distribution->received_by_name : '' }}.
                                </p>
                                <time class="text-xs text-gray-400">
                                    {{ $distribution->received_date?->format('d F Y H:i') ?? 'Tanggal tidak tercatat' }}
                                </time>
                            </li>
                        @endif
                    </ol>
                </div>
            </section>
        </div>

        <div class="space-y-6">
            <section class="rounded-2xl border border-gray-100 bg-white shadow-sm">
                <div class="flex items-center gap-3 border-b border-gray-100 px-6 py-4">
                    <span class="inline-flex items-center justify-center rounded-full bg-purple-50 p-2 text-purple-600">
                        <i class="bi bi-person-fill text-lg"></i>
                    </span>
                    <h2 class="text-lg font-semibold text-gray-900">Informasi Mustahik</h2>
                </div>
                <div class="space-y-5 p-6">
                    <div class="flex flex-col items-center text-center">
                        <div class="flex h-20 w-20 items-center justify-center rounded-full bg-purple-100 text-purple-600">
                            <i class="bi bi-person-fill text-3xl"></i>
                        </div>
                        <p class="mt-3 text-lg font-semibold text-gray-900">{{ $distribution->mustahik->name }}</p>
                        <span class="mt-1 inline-flex rounded-full bg-purple-50 px-3 py-1 text-xs font-semibold text-purple-700">
                            {{ ucfirst(str_replace('_', ' ', $distribution->mustahik->category)) }}
                        </span>
                    </div>

                    <dl class="space-y-4 text-sm text-gray-600">
                        <div class="flex justify-between">
                            <dt class="text-gray-500">NIK</dt>
                            <dd class="font-semibold text-gray-900">{{ $distribution->mustahik->nik ?? '-' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Telepon</dt>
                            <dd class="font-semibold text-gray-900">{{ $distribution->mustahik->phone ?? '-' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Email</dt>
                            <dd class="font-semibold text-gray-900">{{ $distribution->mustahik->email ?? '-' }}</dd>
                        </div>
                    </dl>

                    @if ($distribution->mustahik->address)
                        <div class="rounded-2xl border border-gray-100 bg-gray-50 p-4 text-sm text-gray-600">
                            <h3 class="text-xs font-semibold uppercase tracking-wide text-gray-500">Alamat</h3>
                            <p class="mt-1">{{ $distribution->mustahik->address }}</p>
                        </div>
                    @endif

                    <a href="{{ route('mustahik.show', $distribution->mustahik) }}"
                       class="inline-flex w-full items-center justify-center rounded-xl border border-purple-200 bg-white px-4 py-2.5 text-sm font-semibold text-purple-700 shadow-sm hover:bg-purple-50">
                        <i class="bi bi-eye-fill me-2 text-base"></i>
                        Lihat Profil Mustahik
                    </a>
                </div>
            </section>

            <section class="rounded-2xl border border-gray-100 bg-white shadow-sm">
                <div class="flex items-center gap-3 border-b border-gray-100 px-6 py-4">
                    <span class="inline-flex items-center justify-center rounded-full bg-orange-50 p-2 text-orange-600">
                        <i class="bi bi-lightning-fill text-lg"></i>
                    </span>
                    <h2 class="text-lg font-semibold text-gray-900">Aksi Cepat</h2>
                </div>
                <div class="space-y-3 p-6">
                    @if (! $distribution->is_received)
                        <button type="button" data-open-mark-received
                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-orange-600 px-4 py-2.5 text-sm font-semibold text-white shadow hover:bg-orange-700">
                            <i class="bi bi-check-circle-fill text-base"></i>
                            Tandai Sudah Diterima
                        </button>
                    @endif

                    <a href="{{ route('distributions.edit', $distribution) }}"
                       class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-blue-200 bg-blue-50 px-4 py-2.5 text-sm font-semibold text-blue-700 hover:bg-blue-100">
                        <i class="bi bi-pencil-fill text-base"></i>
                        Edit Distribusi
                    </a>

                    <a href="{{ route('distributions.receipt', $distribution) }}" target="_blank"
                       class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-orange-200 bg-orange-50 px-4 py-2.5 text-sm font-semibold text-orange-700 hover:bg-orange-100">
                        <i class="bi bi-receipt-cutoff text-base"></i>
                        Cetak Kwitansi
                    </a>

                    <a href="{{ route('distributions.create', ['mustahik' => $distribution->mustahik->id]) }}"
                       class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        <i class="bi bi-plus-circle-fill text-base"></i>
                        Distribusi Lagi ke Mustahik Ini
                    </a>

                    @if (! $distribution->is_received)
                        <form action="{{ route('distributions.destroy', $distribution) }}" method="POST"
                              onsubmit="return confirm('Yakin ingin menghapus distribusi ini?')"
                              class="pt-4">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-red-200 bg-red-50 px-4 py-2.5 text-sm font-semibold text-red-700 hover:bg-red-100">
                                <i class="bi bi-trash-fill text-base"></i>
                                Hapus Distribusi
                            </button>
                        </form>
                    @endif
                </div>
            </section>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="markReceivedModal"
     class="fixed inset-0 z-50 hidden items-center justify-center overflow-y-auto overflow-x-hidden bg-gray-900/50 p-4">
    <div class="w-full max-w-lg rounded-2xl bg-white shadow-xl">
        <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
            <h3 class="text-lg font-semibold text-gray-900">Tandai Sebagai Diterima</h3>
            <button type="button" data-close-mark-received class="text-gray-400 hover:text-gray-600">
                <i class="bi bi-x-lg text-base"></i>
            </button>
        </div>
        <form action="{{ route('distributions.mark-received', $distribution) }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="space-y-4 px-6 py-5">
                <p class="text-sm text-gray-600">
                    Konfirmasi bahwa distribusi untuk <span class="font-semibold text-gray-900">{{ $distribution->mustahik->name }}</span> telah diterima.
                </p>
                <div>
                    <label for="received_by_name" class="mb-1 block text-sm font-medium text-gray-700">Diterima Oleh</label>
                    <input type="text" id="received_by_name" name="received_by_name"
                           class="block w-full rounded-xl border border-gray-200 px-3 py-2 text-sm text-gray-900 focus:border-orange-500 focus:ring-orange-500"
                           placeholder="Nama penerima (opsional)">
                </div>
                <div>
                    <label for="received_notes" class="mb-1 block text-sm font-medium text-gray-700">Catatan Penerimaan</label>
                    <textarea id="received_notes" name="received_notes" rows="3"
                              class="block w-full rounded-xl border border-gray-200 px-3 py-2 text-sm text-gray-900 focus:border-orange-500 focus:ring-orange-500"
                              placeholder="Catatan tambahan (opsional)"></textarea>
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 border-t border-gray-100 px-6 py-4">
                <button type="button" data-close-mark-received
                        class="rounded-xl border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit"
                        class="rounded-xl bg-orange-600 px-4 py-2 text-sm font-semibold text-white hover:bg-orange-700">
                    Tandai Diterima
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('markReceivedModal');
    if (!modal) return;

    const toggleModal = (show) => {
        modal.classList.toggle('hidden', !show);
        if (show) {
            modal.classList.add('flex');
        } else {
            modal.classList.remove('flex');
        }
    };

    document.querySelectorAll('[data-open-mark-received]').forEach((btn) => {
        btn.addEventListener('click', () => toggleModal(true));
    });

    modal.querySelectorAll('[data-close-mark-received]').forEach((btn) => {
        btn.addEventListener('click', () => toggleModal(false));
    });

    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            toggleModal(false);
        }
    });
});
</script>
@endpush