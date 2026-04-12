<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-6">Dokumen</h3>
    
    <!-- Profile Photo -->
    <div class="text-center py-6 mb-6 border-b border-gray-100">
        <div class="mb-4">
            <div class="relative inline-block">
                <img src="{{ $muzakki->profile_photo ? asset('storage/' . $muzakki->profile_photo) : asset('images/profile-default.jpg') }}"
                    alt="Profile Photo" class="rounded-full object-cover border-4 border-gray-50 shadow-sm"
                    style="width: 120px; height: 120px;"
                    id="profilePhotoPreview">
            </div>
        </div>
        <p class="text-gray-500 mb-4 text-sm" id="profilePhotoText">{{ $muzakki->profile_photo ? '' : 'Belum ada foto profil' }}</p>
        <button type="button" class="px-6 py-2 bg-orange-600 hover:bg-orange-700 text-white font-medium rounded-full transition-colors shadow-sm"
            onclick="document.getElementById('profilePhotoInput').click()">
            {{ $muzakki->profile_photo ? 'Ganti foto profil' : 'Upload foto profil' }}
        </button>
        <input type="file" id="profilePhotoInput" name="profile_photo" class="hidden" accept="image/*">
    </div>

    <!-- KTP -->
    <div class="mb-4">
        <label for="nik" class="block text-sm font-medium text-gray-700 mb-2">KTP<span class="text-red-500 ml-1">*</span></label>
        <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-orange-500 hover:bg-orange-50 transition-all cursor-pointer group"
            onclick="document.getElementById('ktpInput').click()">
            <img id="ktpPreview" src="{{ $muzakki->ktp_photo ? asset('storage/' . $muzakki->ktp_photo) : '' }}" 
                alt="Preview KTP"
                class="w-full max-w-sm mx-auto h-auto rounded-lg shadow-sm object-contain"
                style="{{ $muzakki->ktp_photo ? '' : 'display: none;' }}">
            <div id="ktpPlaceholder" style="{{ $muzakki->ktp_photo ? 'display: none;' : '' }}">
                <i class="bi bi-plus-circle text-orange-500 text-4xl mb-3 group-hover:scale-110 transition-transform inline-block"></i>
                <p class="text-gray-500 text-sm font-medium">Upload foto KTP</p>
                <p class="text-gray-400 text-xs mt-1">Format: JPG, PNG (Max. 2MB)</p>
            </div>
            <input type="file" id="ktpInput" name="ktp_photo" class="hidden" accept="image/*">
        </div>
        @if (!$muzakki->ktp_photo)
        <div class="mt-3 p-3 bg-yellow-50 border-l-4 border-yellow-400 text-yellow-700 text-sm rounded-r-md flex items-start">
            <i class="bi bi-exclamation-triangle mr-2 mt-0.5"></i>
            <span>Upload foto KTP Anda untuk verifikasi akun</span>
        </div>
        @endif
    </div>
</div>
