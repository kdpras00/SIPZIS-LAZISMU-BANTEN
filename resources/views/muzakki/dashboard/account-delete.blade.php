@extends('layouts.app')

@section('page-title', 'Hapus Akun - SIPZIS Lazismu Banten')

@section('content')
<div class="py-6 px-4 max-w-2xl mx-auto">
    
    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-[#f0ece6]">
        <a href="{{ route('dashboard.management') }}" class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-white border border-[#e8e0d6] text-[#8b7e74] hover:text-[#1c0f0a] hover:bg-[#f0ece6] transition-all shadow-sm">
            <i class="bi bi-arrow-left text-lg"></i>
        </a>
        <div>
            <h1 class="text-xl font-bold text-[#1c0f0a] tracking-tight mb-0.5">Hapus Akun Permanen</h1>
            <p class="text-xs text-[#8b7e74] m-0">Tindakan ini akan menghapus seluruh data diri dan riwayat akun Anda.</p>
        </div>
    </div>

    
    <div class="bg-white rounded-2xl border border-[#f0ece6] p-6 shadow-sm space-y-6">
        
        
        <div class="bg-rose-50/80 border border-rose-200/80 rounded-xl p-4 flex items-start gap-3">
            <div class="w-9 h-9 rounded-lg bg-rose-100 flex items-center justify-center flex-shrink-0 text-rose-700">
                <i class="bi bi-exclamation-triangle-fill text-lg"></i>
            </div>
            <div>
                <h2 class="text-sm font-semibold text-rose-950 m-0">Peringatan Keamanan</h2>
                <p class="text-xs text-rose-800 m-0 mt-1 leading-relaxed">
                    Tindakan menghapus akun bersifat <strong>permanen</strong> dan tidak dapat dibatalkan atau dipulihkan kembali.
                </p>
            </div>
        </div>

        
        <div>
            <h3 class="text-xs font-semibold text-[#1c0f0a] uppercase tracking-wider mb-3">Data yang akan terhapus secara permanen:</h3>
            <div class="space-y-2.5">
                <div class="flex items-start gap-2.5 text-xs text-[#1c0f0a]">
                    <i class="bi bi-x-circle-fill text-rose-500 text-sm mt-0.5"></i>
                    <span>Informasi profil, foto, dan biodata pribadi</span>
                </div>
                <div class="flex items-start gap-2.5 text-xs text-[#1c0f0a]">
                    <i class="bi bi-x-circle-fill text-rose-500 text-sm mt-0.5"></i>
                    <span>Riwayat donasi, zakat, dan penerimaan kwitansi digital</span>
                </div>
                <div class="flex items-start gap-2.5 text-xs text-[#1c0f0a]">
                    <i class="bi bi-x-circle-fill text-rose-500 text-sm mt-0.5"></i>
                    <span>Daftar campaign galang dana yang Anda kelola</span>
                </div>
                <div class="flex items-start gap-2.5 text-xs text-[#1c0f0a]">
                    <i class="bi bi-x-circle-fill text-rose-500 text-sm mt-0.5"></i>
                    <span>Seluruh riwayat rekening bank dan donasi rutin tersimpan</span>
                </div>
            </div>
        </div>

        
        <div class="bg-[#faf8f5] p-4 rounded-xl border border-[#f0ece6] space-y-2">
            <p class="text-xs font-semibold text-[#1c0f0a] m-0">Sebelum melanjutkan, kami menyarankan Anda untuk:</p>
            <ul class="text-xs text-[#8b7e74] space-y-1.5 pl-0 mb-0 list-none">
                <li class="flex items-center gap-2">
                    <i class="bi bi-check2 text-emerald-600 font-bold"></i>
                    <span>Mencatat atau mengunduh kwitansi transaksi penting Anda</span>
                </li>
                <li class="flex items-center gap-2">
                    <i class="bi bi-check2 text-emerald-600 font-bold"></i>
                    <span>Mentransfer kepemilikan campaign aktif jika masih berjalan</span>
                </li>
            </ul>
        </div>

        
        <form id="deleteAccountForm" class="pt-2 border-t border-[#f0ece6] space-y-5">
            <div class="flex items-start gap-3">
                <input class="w-4 h-4 mt-0.5 text-rose-600 border-[#e8e0d6] rounded focus:ring-rose-500/20 cursor-pointer" type="checkbox" id="confirmDelete" required>
                <label class="text-xs text-[#1c0f0a] cursor-pointer leading-relaxed m-0" for="confirmDelete">
                    Saya telah membaca peringatan di atas, memahami konsekuensinya, dan setuju untuk menghapus akun saya secara permanen.
                </label>
            </div>

            <div class="flex items-center justify-between gap-4 pt-2">
                <a href="{{ route('dashboard.management') }}" class="inline-flex items-center justify-center px-4 py-2.5 text-xs font-medium text-[#8b7e74] bg-[#faf8f5] border border-[#e8e0d6] rounded-xl hover:bg-[#f0ece6] hover:text-[#1c0f0a] transition-all">
                    Batal
                </a>
                <button type="button" 
                        class="inline-flex items-center justify-center px-5 py-2.5 text-xs font-medium text-white bg-rose-600 rounded-xl hover:bg-rose-700 active:scale-[0.99] transition-all shadow-sm disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none" 
                        id="confirmDeleteButton" 
                        disabled>
                    <i class="bi bi-trash3 mr-1.5 text-sm"></i> Hapus Akun Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const confirmDeleteCheckbox = document.getElementById('confirmDelete');
        const confirmDeleteButton = document.getElementById('confirmDeleteButton');

        confirmDeleteCheckbox?.addEventListener('change', function() {
            confirmDeleteButton.disabled = !this.checked;
        });

        confirmDeleteButton?.addEventListener('click', function() {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Hapus Akun Permanen?',
                    text: "Apakah Anda yakin ingin menghapus akun ini secara permanen? Tindakan ini tidak dapat dibatalkan.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Hapus Akun',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        confirmDeleteButton.innerHTML = '<i class="bi bi-arrow-repeat spin mr-1"></i> Memproses...';
                        confirmDeleteButton.disabled = true;

                        setTimeout(() => {
                            Swal.fire({
                                icon: 'info',
                                title: 'Permintaan Diterima',
                                text: 'Fitur penghapusan akun mandiri sedang dikaji. Silakan hubungi pengurus Lazismu Banten untuk verifikasi akhir.',
                                confirmButtonColor: '#c2410c'
                            });
                            document.getElementById('deleteAccountForm').reset();
                            confirmDeleteButton.innerHTML = '<i class="bi bi-trash3 mr-1.5 text-sm"></i> Hapus Akun Sekarang';
                            confirmDeleteButton.disabled = true;
                        }, 1200);
                    }
                });
            }
        });
    });
</script>
@endpush
@endsection
