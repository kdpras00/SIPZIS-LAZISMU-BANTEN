@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: @json(session('success')),
            confirmButtonColor: '#c2410c',
            customClass: { popup: 'rounded-2xl' }
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: @json(session('error')),
            confirmButtonColor: '#c2410c',
            customClass: { popup: 'rounded-2xl' }
        });
    @endif

    @if(session('warning'))
        Swal.fire({
            icon: 'warning',
            title: 'Perhatian',
            text: @json(session('warning')),
            confirmButtonColor: '#c2410c',
            customClass: { popup: 'rounded-2xl' }
        });
    @endif

    @if(session('info'))
        Swal.fire({
            icon: 'info',
            title: 'Informasi',
            text: @json(session('info')),
            confirmButtonColor: '#c2410c',
            customClass: { popup: 'rounded-2xl' }
        });
    @endif

    @if($errors->any())
        const errorList = {!! json_encode($errors->all()) !!};
        const htmlContent = `<ul class="text-left text-xs text-red-600 list-disc pl-4 space-y-1">${errorList.map(err => `<li>${err}</li>`).join('')}</ul>`;
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan Validasi',
            html: htmlContent,
            confirmButtonColor: '#c2410c',
            customClass: { popup: 'rounded-2xl' }
        });
    @endif
});
</script>
@endpush