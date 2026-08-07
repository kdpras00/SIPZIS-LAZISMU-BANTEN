@extends('layouts.app')

@section('page-title', 'Buat Donasi Rutin - Dashboard Muzakki')

@section('content')
<div class="py-4 px-4 max-w-4xl mx-auto">
    <div class="flex items-center mb-6">
        <a href="{{ route('dashboard.recurring') }}" class="text-[#8b7e74] mr-3 hover:text-[#1c0f0a]">
            <i class="bi bi-arrow-left-circle text-xl"></i>
        </a>
        <div>
            <h5 class="text-xl font-semibold text-[#1c0f0a] mb-1">Buat Donasi Rutin</h5>
            <p class="text-sm text-[#8b7e74] mb-0">Atur donasi otomatis agar ibadah berbagi tetap konsisten</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-lg p-6 border border-[#f0ece6]">
        <form method="POST" action="{{ route('dashboard.recurring-donations.store') }}" id="recurringForm" class="space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-[#1c0f0a] mb-1.5">Program Donasi <span class="text-red-500">*</span></label>
                @php
                    $progOptions = [];
                    foreach ($programs as $prog) {
                        $progOptions[$prog->id] = $prog->name;
                    }
                @endphp
                <x-custom-select
                    id="program_id"
                    name="program_id"
                    placeholder="Pilih Program Donasi"
                    :selected="old('program_id')"
                    :options="$progOptions"
                />
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#1c0f0a] mb-1.5">Nominal Donasi (Minimal Rp10.000) <span class="text-red-500">*</span></label>
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-xs font-bold" style="color: #8b7e74;">Rp</span>
                    <input type="text" id="amount_display"
                        class="w-full h-11 pl-10 pr-4 rounded-xl border border-[#e8e0d6] bg-white text-xs font-bold text-[#c2410c] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 transition-all outline-none"
                        value="{{ old('amount') ? number_format(old('amount'), 0, ',', '.') : '' }}"
                        placeholder="10.000" required>
                    <input type="hidden" id="amount" name="amount" value="{{ old('amount') }}">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-[#1c0f0a] mb-1.5">Frekuensi Donasi <span class="text-red-500">*</span></label>
                    <x-custom-select
                        id="frequency"
                        name="frequency"
                        placeholder="Pilih Frekuensi"
                        :selected="old('frequency', 'monthly')"
                        :options="['monthly' => 'Bulanan', 'weekly' => 'Mingguan']"
                    />
                </div>
                <div>
                    <label class="block text-xs font-semibold text-[#1c0f0a] mb-1.5">Mulai Tanggal <span class="text-red-500">*</span></label>
                    <x-custom-date-picker
                        id="start_date"
                        name="start_date"
                        :value="old('start_date', date('Y-m-d'))"
                        placeholder="Pilih Tanggal Mulai"
                    />
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#1c0f0a] mb-1.5">Catatan / Doa (Opsional)</label>
                <textarea name="notes" rows="3"
                    class="w-full p-3 rounded-xl border border-[#e8e0d6] bg-white text-xs font-medium text-[#1c0f0a] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 transition-all outline-none"
                    placeholder="Tambahkan doa atau niat khusus">{{ old('notes') }}</textarea>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-[#f0ece6]">
                <a href="{{ route('dashboard.recurring') }}" class="px-5 py-2.5 text-xs font-semibold text-[#1c0f0a] bg-[#f0ece6] rounded-xl transition-colors">Batal</a>
                <button type="submit" class="px-6 py-2.5 text-xs font-semibold text-white bg-[#c2410c] rounded-xl transition-colors shadow-xs">Simpan Donasi Rutin</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const amountDisplay = document.getElementById('amount_display');
    const amountRaw = document.getElementById('amount');
    const form = document.getElementById('recurringForm');

    if (amountDisplay && amountRaw) {
        amountDisplay.addEventListener('input', function() {
            let val = this.value.replace(/[^\d]/g, '');
            amountRaw.value = val;
            this.value = val ? parseInt(val).toLocaleString('id-ID') : '';
        });
    }

    form.addEventListener('submit', function(e) {
        const val = parseFloat(amountRaw.value) || 0;
        if (val < 10000) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Nominal Kurang',
                text: 'Nominal donasi rutin minimal Rp 10.000!',
                confirmButtonColor: '#c2410c'
            });
            amountDisplay.focus();
            return false;
        }
    });
});
</script>
@endpush
@endsection
