<div class="bg-white rounded-2xl border border-[#f0ece6] p-6 mb-6 shadow-sm w-full block">
    <div class="border-b border-[#f0ece6] pb-4 mb-6 w-full">
        <h2 id="personal-heading" class="text-sm font-bold text-[#1c0f0a] m-0 tracking-tight">Data Diri & Kontak</h2>
        <p class="text-xs text-[#8b7e74] m-0 mt-0.5">Informasi utama identitas dan kontak akun Anda.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        
        <div class="md:col-span-2">
            <label for="name" class="block text-xs font-semibold text-[#8b7e74] mb-1.5 uppercase tracking-wider">Nama Lengkap <span class="text-rose-500">*</span></label>
            <input type="text" 
                   class="w-full h-11 px-4 rounded-xl border border-[#e8e0d6] bg-white text-xs font-medium text-[#1c0f0a] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 transition-all outline-none @error('name') border-rose-500 @enderror"
                   id="name" 
                   name="name" 
                   value="{{ old('name', $muzakki->name) }}" 
                   required
                   aria-required="true">
            @error('name')
            <p class="mt-1 text-xs text-rose-500 m-0" role="alert">{{ $message }}</p>
            @enderror
        </div>

        
        <div>
            <label for="email" class="block text-xs font-semibold text-[#8b7e74] mb-1.5 uppercase tracking-wider">Alamat Email <span class="text-rose-500">*</span></label>
            <input type="email" 
                   class="w-full h-11 px-4 rounded-xl border border-[#e8e0d6] bg-[#faf8f5] text-xs font-medium text-[#8b7e74] cursor-not-allowed outline-none"
                   id="email" 
                   name="email" 
                   value="{{ old('email', $muzakki->email) }}" 
                   readonly
                   aria-readonly="true">
        </div>

        
        <div>
            <label for="phone" class="block text-xs font-semibold text-[#8b7e74] mb-1.5 uppercase tracking-wider">Nomor WhatsApp / Telepon <span class="text-rose-500">*</span></label>
            
            @if(!$muzakki->phone_verified)
            <div class="flex items-center">
                <div class="relative flex-1 min-w-0">
                    <input type="tel" 
                           id="phone" 
                           name="phone" 
                           class="w-full h-11 rounded-l-xl border border-r-0 border-[#e8e0d6] bg-white text-[#1c0f0a] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 text-xs font-medium transition-all outline-none"
                           value="{{ old('phone', preg_replace('/^\+62|^62|^0/', '', $muzakki->phone ?? '')) }}"
                           placeholder="8xxxxxxxxxx"
                           aria-label="Nomor Telepon WhatsApp">
                </div>
                <button type="button" 
                        class="inline-flex items-center justify-center h-11 px-4 bg-[#c2410c] hover:bg-[#9a3412] text-white font-semibold text-xs rounded-r-xl border border-[#c2410c] transition-all shadow-2xs whitespace-nowrap"
                        id="verifyPhoneBtn">
                    <i class="bi bi-shield-check mr-1.5 text-sm" aria-hidden="true"></i> Verifikasi OTP
                </button>
            </div>
            @else
            <div class="flex items-center gap-3">
                <div class="relative flex-1 min-w-0">
                    <input type="tel" 
                           id="phone" 
                           name="phone" 
                           class="w-full h-11 px-4 rounded-xl border border-[#e8e0d6] bg-[#faf8f5] text-[#8b7e74] cursor-not-allowed text-xs font-medium outline-none"
                           value="{{ old('phone', preg_replace('/^\+62|^62|^0/', '', $muzakki->phone ?? '')) }}"
                           readonly
                           aria-readonly="true">
                </div>
                <span id="verifyCheckmark" class="text-emerald-600 flex items-center gap-1 text-xs font-semibold whitespace-nowrap" title="Terverifikasi">
                    <i class="bi bi-patch-check-fill text-base" aria-hidden="true"></i> Terverifikasi
                </span>
            </div>
            @endif

            @if (!$muzakki->phone_verified)
            <aside class="mt-2 p-2.5 bg-amber-50/80 border border-amber-200/80 rounded-xl flex items-center gap-2" id="notVerifiedAlert" role="alert">
                <i class="bi bi-exclamation-circle text-amber-700 text-xs flex-shrink-0" aria-hidden="true"></i>
                <span class="text-[11px] text-amber-900 leading-tight">Verifikasi telepon diperlukan untuk keamanan transaksi.</span>
            </aside>
            @endif

            @error('phone')
            <p class="mt-1 text-xs text-rose-500 m-0" role="alert">{{ $message }}</p>
            @enderror
        </div>

        
        <div class="md:col-span-2">
            <label for="campaign_url" class="block text-xs font-semibold text-[#8b7e74] mb-1.5 uppercase tracking-wider">Tautan Halaman Campaigner</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#8b7e74]" aria-hidden="true">
                    <i class="bi bi-link-45deg text-base"></i>
                </div>
                <input type="url" 
                       class="w-full h-11 pl-9 pr-4 rounded-xl border border-[#e8e0d6] bg-[#faf8f5] text-xs text-[#8b7e74] cursor-not-allowed outline-none"
                       id="campaign_url" 
                       name="campaign_url" 
                       value="{{ old('campaign_url', $muzakki->campaign_url) }}" 
                       readonly
                       aria-readonly="true">
            </div>
        </div>

        
        <div>
            <label for="gender" class="block text-xs font-semibold text-[#8b7e74] mb-1.5 uppercase tracking-wider">Jenis Kelamin <span class="text-rose-500">*</span></label>
            <div class="relative">
                <select class="w-full h-11 pl-4 pr-9 rounded-xl border border-[#e8e0d6] bg-white text-xs font-medium text-[#1c0f0a] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 transition-all appearance-none outline-none cursor-pointer" 
                        id="gender" 
                        name="gender" 
                        required
                        aria-required="true">
                    <option value="">-- Pilih Jenis Kelamin --</option>
                    <option value="male" {{ old('gender', $muzakki->gender) == 'male' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="female" {{ old('gender', $muzakki->gender) == 'female' ? 'selected' : '' }}>Perempuan</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3.5 text-[#8b7e74]" aria-hidden="true">
                    <i class="bi bi-chevron-down text-xs"></i>
                </div>
            </div>
        </div>

        
        <div>
            <label class="block text-xs font-semibold text-[#8b7e74] mb-1.5 uppercase tracking-wider">Tanggal Lahir <span class="text-rose-500">*</span></label>
            <div class="grid grid-cols-3 gap-2">
                <div class="relative">
                    <select class="w-full h-11 pl-3 pr-7 rounded-xl border border-[#e8e0d6] bg-white text-xs font-medium text-[#1c0f0a] focus:border-[#c2410c] appearance-none outline-none cursor-pointer" name="birth_day" aria-label="Hari Lahir">
                        <option value="">Hari</option>
                        @for ($i = 1; $i <= 31; $i++)
                        <option value="{{ $i }}" {{ old('birth_day', $muzakki->date_of_birth ? $muzakki->date_of_birth->day : '') == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2 text-[#8b7e74]" aria-hidden="true">
                        <i class="bi bi-chevron-down text-[10px]"></i>
                    </div>
                </div>

                <div class="relative">
                    <select class="w-full h-11 pl-3 pr-7 rounded-xl border border-[#e8e0d6] bg-white text-xs font-medium text-[#1c0f0a] focus:border-[#c2410c] appearance-none outline-none cursor-pointer" name="birth_month" aria-label="Bulan Lahir">
                        <option value="">Bulan</option>
                        @php $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des']; @endphp
                        @foreach ($months as $index => $month)
                        <option value="{{ $index + 1 }}" {{ old('birth_month', $muzakki->date_of_birth ? $muzakki->date_of_birth->month : '') == $index + 1 ? 'selected' : '' }}>{{ $month }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2 text-[#8b7e74]" aria-hidden="true">
                        <i class="bi bi-chevron-down text-[10px]"></i>
                    </div>
                </div>

                <div class="relative">
                    <select class="w-full h-11 pl-3 pr-7 rounded-xl border border-[#e8e0d6] bg-white text-xs font-medium text-[#1c0f0a] focus:border-[#c2410c] appearance-none outline-none cursor-pointer" name="birth_year" aria-label="Tahun Lahir">
                        <option value="">Tahun</option>
                        @for ($i = date('Y'); $i >= 1940; $i--)
                        <option value="{{ $i }}" {{ old('birth_year', $muzakki->date_of_birth ? $muzakki->date_of_birth->year : '') == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2 text-[#8b7e74]" aria-hidden="true">
                        <i class="bi bi-chevron-down text-[10px]"></i>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="md:col-span-2">
            <label for="occupation" class="block text-xs font-semibold text-[#8b7e74] mb-1.5 uppercase tracking-wider">Profesi / Pekerjaan <span class="text-rose-500">*</span></label>
            <div class="relative">
                <select class="w-full h-11 pl-4 pr-9 rounded-xl border border-[#e8e0d6] bg-white text-xs font-medium text-[#1c0f0a] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 transition-all appearance-none outline-none cursor-pointer"
                        id="occupation" 
                        name="occupation">
                    <option value="">-- Pilih Profesi --</option>
                    @php
                    $occupations = ['Karyawan', 'Wiraswasta', 'PNS', 'Guru', 'Dokter', 'Perawat', 'Tentara', 'Polisi', 'Petani', 'Nelayan', 'Pedagang', 'Sopir', 'Ojek Online', 'Programmer', 'Desainer', 'Akuntan', 'Mahasiswa', 'Pelajar', 'Ibu Rumah Tangga', 'Pensiunan', 'Seniman', 'Musisi', 'Atlet', 'Pengacara', 'Arsitek', 'Lainnya'];
                    @endphp
                    @foreach ($occupations as $occupation)
                    <option value="{{ strtolower(str_replace(' ', '_', $occupation)) }}"
                        {{ old('occupation', $muzakki->occupation) == strtolower(str_replace(' ', '_', $occupation)) ? 'selected' : '' }}>
                        {{ $occupation }}
                    </option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3.5 text-[#8b7e74]" aria-hidden="true">
                    <i class="bi bi-chevron-down text-xs"></i>
                </div>
            </div>
        </div>

        
        <div class="md:col-span-2">
            <label for="bio" class="block text-xs font-semibold text-[#8b7e74] mb-1.5 uppercase tracking-wider">Biodata / Profil Singkat</label>
            <textarea name="bio" 
                      id="bio" 
                      rows="3" 
                      class="w-full px-4 py-3 rounded-xl border border-[#e8e0d6] bg-white text-xs font-medium text-[#1c0f0a] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 transition-all outline-none leading-relaxed"
                      placeholder="Tuliskan kisah atau motivasi singkat Anda berdonasi di Lazismu Banten...">{{ old('bio', $muzakki->bio) }}</textarea>
            <p class="text-[11px] text-[#8b7e74] mt-1 m-0">Profil singkat yang inspiratif memudahkan masyarakat mengenai kiprah kebaikan Anda.</p>
        </div>
    </div>
</div>
