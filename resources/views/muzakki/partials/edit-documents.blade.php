<div class="bg-white rounded-2xl border border-[#f0ece6] p-6 mb-6 shadow-sm w-full block">
    <div class="border-b border-[#f0ece6] pb-4 mb-6 w-full">
        <h2 id="documents-heading" class="text-sm font-bold text-[#1c0f0a] m-0 tracking-tight">Dokumen & Identitas</h2>
        <p class="text-xs text-[#8b7e74] m-0 mt-0.5">Unggah pas foto terbaru dan identitas KTP Anda.</p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
        
        <article class="p-5 rounded-2xl bg-[#faf8f5] border border-[#e8e0d6] text-center flex flex-col items-center justify-between h-full min-h-[220px]">
            <div class="w-full">
                <label class="block text-xs font-semibold text-[#8b7e74] mb-3 uppercase tracking-wider text-left">Foto Profil</label>
                <figure class="relative inline-block mb-3 m-0">
                    @php
                        $hasAvatar = Auth::check() && Auth::user()->avatar;
                        $hasProfilePhoto = $muzakki->profile_photo ? true : false;
                        $avatarSrc = $hasProfilePhoto ? asset('storage/' . $muzakki->profile_photo) : ($hasAvatar ? Auth::user()->avatar : '');
                        $showAvatar = $hasProfilePhoto || $hasAvatar;
                    @endphp
                    <img src="{{ $avatarSrc }}"
                        alt="Foto Profil {{ $muzakki->name }}" 
                        class="w-20 h-20 rounded-full object-cover border-2 border-white shadow-sm mx-auto"
                        id="profilePhotoPreview"
                        style="{{ $showAvatar ? '' : 'display: none;' }}">
                    
                    <div id="defaultAvatarIcon" 
                         class="w-20 h-20 rounded-full bg-white border-2 border-[#e8e0d6] flex items-center justify-center text-[#c2410c] text-3xl mx-auto shadow-2xs"
                         style="{{ $showAvatar ? 'display: none;' : '' }}"
                         aria-hidden="true">
                        <i class="bi bi-person-fill"></i>
                    </div>
                </figure>
                <p class="text-xs text-[#8b7e74] mb-3" id="profilePhotoText">{{ $showAvatar ? 'Foto profil aktif' : 'Belum ada foto profil tersimpan' }}</p>
            </div>
            
            <button type="button" 
                    class="w-full inline-flex items-center justify-center gap-1.5 px-4 py-2.5 bg-white border border-[#e8e0d6] text-[#1c0f0a] hover:bg-[#f0ece6] font-semibold text-xs rounded-xl transition-all shadow-2xs"
                    onclick="document.getElementById('profilePhotoInput').click()">
                <i class="bi bi-camera text-sm text-[#c2410c]" aria-hidden="true"></i>
                {{ $muzakki->profile_photo ? 'Ubah Foto Profil' : 'Unggah Foto Profil' }}
            </button>
            <input type="file" id="profilePhotoInput" name="profile_photo" class="hidden" accept="image/*" aria-label="Unggah Foto Profil">
        </article>

        
        <article class="p-5 rounded-2xl bg-[#faf8f5] border border-[#e8e0d6] flex flex-col justify-between h-full min-h-[220px]">
            <div>
                <label for="ktpInput" class="block text-xs font-semibold text-[#8b7e74] mb-3 uppercase tracking-wider">Foto KTP / Kartu Identitas <span class="text-rose-500">*</span></label>
                
                <div class="border border-dashed border-[#e8e0d6] rounded-xl p-4 text-center hover:border-[#c2410c] hover:bg-white transition-all cursor-pointer bg-white group"
                    onclick="document.getElementById('ktpInput').click()">
                    <img id="ktpPreview" src="{{ $muzakki->ktp_photo ? asset('storage/' . $muzakki->ktp_photo) : '' }}" 
                        alt="Preview KTP {{ $muzakki->name }}"
                        class="w-full max-w-xs mx-auto h-auto rounded-lg shadow-2xs object-contain"
                        style="{{ $muzakki->ktp_photo ? '' : 'display: none;' }}">
                    <div id="ktpPlaceholder" style="{{ $muzakki->ktp_photo ? 'display: none;' : '' }}" class="py-2 space-y-1">
                        <div class="w-9 h-9 rounded-full bg-[#faf8f5] border border-[#e8e0d6] flex items-center justify-center mx-auto text-[#c2410c] group-hover:scale-105 transition-transform">
                            <i class="bi bi-card-image text-base" aria-hidden="true"></i>
                        </div>
                        <p class="text-xs font-semibold text-[#1c0f0a] m-0">Klik untuk mengunggah foto KTP</p>
                        <p class="text-[10px] text-[#8b7e74] m-0">Format: JPG, PNG (Maksimal 2MB)</p>
                    </div>
                    <input type="file" id="ktpInput" name="ktp_photo" class="hidden" accept="image/*" aria-label="Unggah Kartu Identitas KTP">
                </div>
            </div>

            @if (!$muzakki->ktp_photo)
            <aside class="mt-3 p-2.5 bg-amber-50/80 border border-amber-200/80 rounded-xl flex items-center gap-2" role="alert">
                <i class="bi bi-shield-exclamation text-amber-700 text-xs flex-shrink-0" aria-hidden="true"></i>
                <span class="text-[11px] text-amber-900 leading-tight">Unggah foto KTP Anda untuk verifikasi identitas akun.</span>
            </aside>
            @endif
        </article>
    </div>
</div>
