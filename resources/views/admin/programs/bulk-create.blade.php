@extends('layouts.app')

@section('page-title', 'Tambah Program Massal')

@section('content')
<div class="px-4 sm:px-6 py-5 w-full mx-auto" style="max-width: 1280px;">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-6">
        <div>
            <h2 class="text-xl font-bold mb-1" style="color: #1c0f0a;">Tambah Program Secara Massal</h2>
            <p class="text-sm" style="color: #8b7e74;">Buat beberapa program sekaligus ke dalam sistem</p>
        </div>
        <a href="{{ route('admin.programs.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-medium transition-colors duration-200"
            style="background: #f0ece6; color: #1c0f0a;">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="rounded-2xl p-5 sm:p-6 bg-white border border-[#f0ece6]" style="box-shadow: 0 1px 3px rgba(28,15,10,0.04);">
        <form action="{{ route('admin.programs.store.bulk') }}" method="POST">
            @csrf

            @if ($errors->any())
            <div class="p-4 rounded-xl mb-4 bg-red-50 border border-red-200 text-xs text-red-700">
                <ul class="list-disc pl-4 space-y-1">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="p-4 rounded-xl mb-6 bg-orange-50/70 border border-orange-100 flex items-center gap-3 text-xs text-[#1c0f0a]">
                <i class="bi bi-info-circle-fill text-base text-[#c2410c]"></i>
                <span>Tambahkan beberapa program sekaligus dengan mengisi formulir dinamis di bawah ini.</span>
            </div>

            <div class="flex items-center justify-between mb-5">
                <h3 class="text-sm font-semibold" style="color: #1c0f0a;">Daftar Form Program</h3>
                <button type="button" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold text-white transition-all shadow-xs" style="background: #c2410c;" id="add-program">
                    <i class="bi bi-plus-lg"></i> Tambah Form Program
                </button>
            </div>

            <div id="programs-container" class="space-y-4">
                <!-- Program forms will be added here dynamically -->
            </div>

            <div class="flex items-center justify-between mt-6 pt-5 border-t border-[#f0ece6]">
                <a href="{{ route('admin.programs.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-semibold" style="background: #f0ece6; color: #1c0f0a;">
                    <i class="bi bi-arrow-left"></i> Batal
                </a>
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-xs font-semibold text-white transition-all shadow-xs" style="background: #c2410c;">
                    <i class="bi bi-check-lg text-sm"></i> Simpan Semua Program
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('programs-container');
        const addButton = document.getElementById('add-program');
        let programIndex = 0;

        // Function to create a new program form
        function createProgramForm() {
            const programDiv = document.createElement('div');
            programDiv.className = 'program-form rounded-2xl p-5 border border-[#f0ece6] bg-[#faf8f5]/50 relative';
            programDiv.innerHTML = `
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-[#f0ece6]">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-[#c2410c]">Program #${programIndex + 1}</h4>
                    <button type="button" class="remove-program px-3 py-1.5 rounded-lg text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 transition-colors">
                        <i class="bi bi-trash mr-1"></i> Hapus
                    </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-semibold mb-1 text-[#1c0f0a]">Nama Program *</label>
                        <input class="w-full h-11 px-4 rounded-xl border border-[#e8e0d6] bg-white text-xs font-medium text-[#1c0f0a] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 transition-all outline-none" type="text" name="programs[${programIndex}][name]" placeholder="Nama program" required>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold mb-1 text-[#1c0f0a]">Kategori *</label>
                        <div class="relative">
                            <select class="w-full h-11 px-4 pr-9 rounded-xl border border-[#e8e0d6] bg-white text-xs font-medium text-[#1c0f0a] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 transition-all appearance-none outline-none cursor-pointer" name="programs[${programIndex}][category]" required>
                                <option value="">Pilih Kategori</option>
                                <option value="zakat">Zakat</option>
                                <option value="infaq">Infaq</option>
                                <option value="shadaqah">Shadaqah</option>
                                <option value="pendidikan">Pendidikan</option>
                                <option value="kesehatan">Kesehatan</option>
                                <option value="ekonomi">Ekonomi</option>
                                <option value="sosial-dakwah">Sosial & Dakwah</option>
                                <option value="kemanusiaan">Kemanusiaan</option>
                                <option value="lingkungan">Lingkungan</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3.5 text-gray-500">
                                <i class="bi bi-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-semibold mb-1 text-[#1c0f0a]">Target Dana (Rp)</label>
                        <input class="w-full h-11 px-4 rounded-xl border border-[#e8e0d6] bg-white text-xs font-medium text-[#1c0f0a] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 transition-all outline-none target-amount-input" type="text" name="programs[${programIndex}][target_amount_display]" placeholder="0">
                        <input type="hidden" name="programs[${programIndex}][target_amount]" class="target-amount-raw">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold mb-1 text-[#1c0f0a]">Status *</label>
                        <div class="relative">
                            <select class="w-full h-11 px-4 pr-9 rounded-xl border border-[#e8e0d6] bg-white text-xs font-medium text-[#1c0f0a] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 transition-all appearance-none outline-none cursor-pointer" name="programs[${programIndex}][status]" required>
                                <option value="active">Aktif</option>
                                <option value="inactive">Tidak Aktif</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3.5 text-gray-500">
                                <i class="bi bi-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold mb-1 text-[#1c0f0a]">Deskripsi</label>
                    <textarea class="w-full p-3 rounded-xl border border-[#e8e0d6] bg-white text-xs font-medium text-[#1c0f0a] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 transition-all outline-none" name="programs[${programIndex}][description]" rows="2" placeholder="Deskripsi singkat..."></textarea>
                </div>
            `;
            container.appendChild(programDiv);
            programIndex++;

            // Add event listener for remove button
            const removeButton = programDiv.querySelector('.remove-program');
            removeButton.addEventListener('click', function() {
                programDiv.remove();
                updateProgramNumbers();
            });

            // Add format number functionality to the new input
            const targetAmountInput = programDiv.querySelector('.target-amount-input');
            const targetAmountRaw = programDiv.querySelector('.target-amount-raw');
            
            if (targetAmountInput && targetAmountRaw) {
                function formatNumberWithCommas(input) {
                    let value = input.value.replace(/[^\d]/g, '');
                    if (value) {
                        value = parseInt(value).toLocaleString('id-ID');
                    }
                    input.value = value;
                    targetAmountRaw.value = input.value.replace(/[^\d]/g, '');
                }

                targetAmountInput.addEventListener('input', function() {
                    formatNumberWithCommas(this);
                });
            }
        }

        // Function to update program numbers
        function updateProgramNumbers() {
            const programForms = document.querySelectorAll('.program-form');
            programForms.forEach((form, index) => {
                const header = form.querySelector('h4');
                if (header) header.textContent = `Program #${index + 1}`;
            });
        }

        // Add initial program form
        createProgramForm();

        // Add event listener for add button
        addButton.addEventListener('click', createProgramForm);

        // Update hidden inputs sebelum submit form
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const allAmountInputs = document.querySelectorAll('.target-amount-input');
                allAmountInputs.forEach(function(input) {
                    const rawValue = input.value.replace(/[^\d]/g, '');
                    const rawInput = input.parentElement.querySelector('.target-amount-raw');
                    if (rawInput) {
                        rawInput.value = rawValue;
                    }
                });
            });
        }
    });
</script>
@endpush