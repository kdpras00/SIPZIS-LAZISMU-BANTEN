<div class="bg-white rounded-2xl border border-[#f0ece6] p-6 mb-6 shadow-sm w-full block">
    <div class="border-b border-[#f0ece6] pb-4 mb-6 w-full">
        <h2 id="address-heading" class="text-sm font-bold text-[#1c0f0a] m-0 tracking-tight">Alamat Tempat Tinggal</h2>
        <p class="text-xs text-[#8b7e74] m-0 mt-0.5">Alamat domisili dan lokasi penyaluran donasi/zakat.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <!-- Negara -->
        <div>
            <label for="country" class="block text-xs font-semibold text-[#8b7e74] mb-1.5 uppercase tracking-wider">Negara <span class="text-rose-500">*</span></label>
            <div class="relative">
                <select class="w-full h-11 pl-4 pr-9 rounded-xl border border-[#e8e0d6] bg-white text-xs font-medium text-[#1c0f0a] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 transition-all appearance-none outline-none cursor-pointer"
                        id="country" 
                        name="country" 
                        required
                        aria-required="true">
                    <option value="">-- Pilih Negara --</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3.5 text-[#8b7e74]" aria-hidden="true">
                    <i class="bi bi-chevron-down text-xs"></i>
                </div>
            </div>
        </div>

        <!-- Kode Pos -->
        <div>
            <label for="postal_code" class="block text-xs font-semibold text-[#8b7e74] mb-1.5 uppercase tracking-wider">Kode Pos <span class="text-rose-500">*</span></label>
            <input type="text" 
                   class="w-full h-11 px-4 rounded-xl border border-[#e8e0d6] bg-white text-xs font-medium text-[#1c0f0a] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 transition-all outline-none"
                   id="postal_code" 
                   name="postal_code" 
                   value="{{ old('postal_code', $muzakki->postal_code) }}" 
                   maxlength="5"
                   placeholder="40xxx"
                   inputmode="numeric">
        </div>

        <!-- Provinsi -->
        <div>
            <label for="province" class="block text-xs font-semibold text-[#8b7e74] mb-1.5 uppercase tracking-wider">Provinsi <span class="text-rose-500">*</span></label>
            <div class="relative">
                <select class="w-full h-11 pl-4 pr-9 rounded-xl border border-[#e8e0d6] bg-white text-xs font-medium text-[#1c0f0a] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 transition-all appearance-none outline-none cursor-pointer"
                        id="province" 
                        name="province">
                    <option value="">-- Pilih Provinsi --</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3.5 text-[#8b7e74]" aria-hidden="true">
                    <i class="bi bi-chevron-down text-xs"></i>
                </div>
            </div>
        </div>

        <!-- Kota / Kabupaten -->
        <div>
            <label for="city" class="block text-xs font-semibold text-[#8b7e74] mb-1.5 uppercase tracking-wider">Kota / Kabupaten <span class="text-rose-500">*</span></label>
            <div class="relative">
                <select class="w-full h-11 pl-4 pr-9 rounded-xl border border-[#e8e0d6] bg-white text-xs font-medium text-[#1c0f0a] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 transition-all appearance-none outline-none cursor-pointer"
                        id="city" 
                        name="city">
                    <option value="">-- Pilih Kota / Kabupaten --</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3.5 text-[#8b7e74]" aria-hidden="true">
                    <i class="bi bi-chevron-down text-xs"></i>
                </div>
            </div>
        </div>

        <!-- Kecamatan -->
        <div>
            <label for="district" class="block text-xs font-semibold text-[#8b7e74] mb-1.5 uppercase tracking-wider">Kecamatan <span class="text-rose-500">*</span></label>
            <div class="relative">
                <select class="w-full h-11 pl-4 pr-9 rounded-xl border border-[#e8e0d6] bg-white text-xs font-medium text-[#1c0f0a] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 transition-all appearance-none outline-none cursor-pointer" 
                        id="district" 
                        name="district">
                    <option value="">-- Pilih Kecamatan --</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3.5 text-[#8b7e74]" aria-hidden="true">
                    <i class="bi bi-chevron-down text-xs"></i>
                </div>
            </div>
        </div>

        <!-- Kelurahan / Desa -->
        <div>
            <label for="village" class="block text-xs font-semibold text-[#8b7e74] mb-1.5 uppercase tracking-wider">Kelurahan / Desa <span class="text-rose-500">*</span></label>
            <div class="relative">
                <select class="w-full h-11 pl-4 pr-9 rounded-xl border border-[#e8e0d6] bg-white text-xs font-medium text-[#1c0f0a] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 transition-all appearance-none outline-none cursor-pointer" 
                        id="village" 
                        name="village">
                    <option value="">-- Pilih Kelurahan / Desa --</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3.5 text-[#8b7e74]" aria-hidden="true">
                    <i class="bi bi-chevron-down text-xs"></i>
                </div>
            </div>
        </div>

        <!-- Alamat Lengkap (Full Width) -->
        <div class="md:col-span-2">
            <label for="address" class="block text-xs font-semibold text-[#8b7e74] mb-1.5 uppercase tracking-wider">Alamat Lengkap (Jalan, RT/RW, No. Rumah) <span class="text-rose-500">*</span></label>
            <textarea class="w-full px-4 py-3 rounded-xl border border-[#e8e0d6] bg-white text-xs font-medium text-[#1c0f0a] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 transition-all outline-none leading-relaxed"
                      id="address" 
                      name="address" 
                      rows="3"
                      placeholder="Masukkan alamat lengkap tempat tinggal Anda...">{{ old('address', $muzakki->address) }}</textarea>
        </div>
    </div>
</div>
