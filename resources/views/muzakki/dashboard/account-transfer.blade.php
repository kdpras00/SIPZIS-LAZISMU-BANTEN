@extends('layouts.app')

@section('page-title', 'Transfer Kepemilikan Campaign - SIPZIS Lazismu Banten')

@section('content')
<div class="py-6 px-4 max-w-2xl mx-auto">
    <!-- Header Navigation -->
    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-[#f0ece6]">
        <a href="{{ route('dashboard.management') }}" class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-white border border-[#e8e0d6] text-[#8b7e74] hover:text-[#1c0f0a] hover:bg-[#f0ece6] transition-all shadow-sm">
            <i class="bi bi-arrow-left text-lg"></i>
        </a>
        <div>
            <h1 class="text-xl font-bold text-[#1c0f0a] tracking-tight mb-0.5">Transfer Kepemilikan Campaign</h1>
            <p class="text-xs text-[#8b7e74] m-0">Alihkan hak pengelola campaign galang dana ke akun muzakki lain.</p>
        </div>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-2xl border border-[#f0ece6] p-6 shadow-sm space-y-6">
        
        <!-- Info Banner -->
        <div class="bg-amber-50/80 border border-amber-200/80 rounded-xl p-4 flex items-start gap-3">
            <div class="w-9 h-9 rounded-lg bg-amber-100 flex items-center justify-center flex-shrink-0 text-amber-700">
                <i class="bi bi-shield-exclamation text-lg"></i>
            </div>
            <div>
                <h2 class="text-sm font-semibold text-amber-950 m-0">Perhatian Sebelum Transfer</h2>
                <p class="text-xs text-amber-800 m-0 mt-1 leading-relaxed">
                    Setelah proses pengalihan selesai, Anda tidak lagi memiliki hak akses penuh untuk mengelola atau menyunting campaign tersebut.
                </p>
            </div>
        </div>

        <!-- Transfer Form -->
        <form id="transferOwnershipForm" class="space-y-4">
            <div>
                <label for="campaign_select" class="block text-xs font-medium text-[#8b7e74] mb-1.5 uppercase tracking-wider">Pilih Campaign</label>
                <select class="w-full px-4 py-2.5 rounded-xl border border-[#e8e0d6] bg-white text-xs font-medium text-[#1c0f0a] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 transition-all outline-none" id="campaign_select" name="campaign_select" required>
                    <option value="">-- Pilih campaign yang akan dialihkan --</option>
                    <option value="1">Campaign Pendidikan Anak Yatim Banten</option>
                    <option value="2">Program Bantuan Pangan Kaum Dhuafa</option>
                    <option value="3">Renovasi Masjid & Musholla Pelosok</option>
                </select>
            </div>

            <div>
                <label for="new_owner_email" class="block text-xs font-medium text-[#8b7e74] mb-1.5 uppercase tracking-wider">Email Pemilik Baru</label>
                <input type="email" 
                       class="w-full px-4 py-2.5 rounded-xl border border-[#e8e0d6] bg-white text-xs font-medium text-[#1c0f0a] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 transition-all outline-none" 
                       id="new_owner_email" 
                       name="new_owner_email" 
                       placeholder="nama@email.com" 
                       required>
                <p class="text-[11px] text-[#8b7e74] mt-1.5 m-0">Pemilik baru akan menerima notifikasi dan instruksi konfirmasi via email.</p>
            </div>

            <div class="flex items-center justify-between gap-4 pt-4 border-t border-[#f0ece6]">
                <a href="{{ route('dashboard.management') }}" class="inline-flex items-center justify-center px-4 py-2.5 text-xs font-medium text-[#8b7e74] bg-[#faf8f5] border border-[#e8e0d6] rounded-xl hover:bg-[#f0ece6] hover:text-[#1c0f0a] transition-all">
                    Batal
                </a>
                <button type="button" 
                        class="inline-flex items-center justify-center px-5 py-2.5 text-xs font-semibold text-white bg-[#c2410c] rounded-xl hover:bg-[#9a3412] active:scale-[0.99] transition-all shadow-sm hover:shadow-md" 
                        id="confirmTransferButton">
                    <i class="bi bi-box-arrow-right mr-1.5 text-sm"></i> Transfer Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const confirmTransferButton = document.getElementById('confirmTransferButton');
        confirmTransferButton?.addEventListener('click', function() {
            const campaignSelect = document.getElementById('campaign_select');
            const emailInput = document.getElementById('new_owner_email');
            const campaignName = campaignSelect.options[campaignSelect.selectedIndex]?.text || '';

            if (!campaignSelect.value || !emailInput.value) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Data Belum Lengkap',
                        text: 'Mohon pilih campaign dan masukkan email penerima.',
                        confirmButtonColor: '#c2410c'
                    });
                } else {
                    alert('Mohon lengkapi semua field.');
                }
                return;
            }

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Konfirmasi Pengalihan',
                    html: `Apakah Anda yakin ingin mentransfer kepemilikan <strong>"${campaignName}"</strong> ke <code>${emailInput.value}</code>?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#c2410c',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Transfer',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        confirmTransferButton.innerHTML = '<i class="bi bi-arrow-repeat spin mr-1"></i> Memproses...';
                        confirmTransferButton.disabled = true;

                        setTimeout(() => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Permintaan Dikirim',
                                text: 'Undangan transfer telah dikirimkan ke email penerima.',
                                confirmButtonColor: '#c2410c'
                            });
                            document.getElementById('transferOwnershipForm').reset();
                            confirmTransferButton.innerHTML = '<i class="bi bi-box-arrow-right mr-1.5 text-sm"></i> Transfer Sekarang';
                            confirmTransferButton.disabled = false;
                        }, 1200);
                    }
                });
            }
        });
    });
</script>
@endpush
@endsection
