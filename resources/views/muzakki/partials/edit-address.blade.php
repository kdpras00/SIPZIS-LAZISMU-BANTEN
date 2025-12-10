<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-6">Alamat</h3>

    <div class="mb-5">
        <label for="country" class="block text-sm font-medium text-gray-700 mb-2">Negara<span class="text-red-500 ml-1">*</span></label>
        <select class="w-full bg-gray-50 border border-gray-300 rounded-lg py-3 px-4 shadow-sm focus:bg-white focus:border-green-500 focus:ring-green-500 transition-all @error('country') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
            id="country" name="country" required>
            <option value="">Pilih Negara</option>
            <!-- Populated by JS -->
        </select>
        @error('country')
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-5">
        <div>
            <label for="province" class="block text-sm font-medium text-gray-700 mb-2">Provinsi<span class="text-red-500 ml-1">*</span></label>
            <select class="w-full bg-gray-50 border border-gray-300 rounded-lg py-3 px-4 shadow-sm focus:bg-white focus:border-green-500 focus:ring-green-500 transition-all @error('province') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                id="province" name="province">
                <option value="">Pilih Provinsi</option>
            </select>
            @error('province')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="city" class="block text-sm font-medium text-gray-700 mb-2">Kota/Kabupaten<span class="text-red-500 ml-1">*</span></label>
            <select class="w-full bg-gray-50 border border-gray-300 rounded-lg py-3 px-4 shadow-sm focus:bg-white focus:border-green-500 focus:ring-green-500 transition-all @error('city') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                id="city" name="city">
                <option value="">Pilih Kota/Kabupaten</option>
            </select>
            @error('city')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-5">
        <div>
            <label for="district" class="block text-sm font-medium text-gray-700 mb-2">Kecamatan<span class="text-red-500 ml-1">*</span></label>
            <select class="w-full bg-gray-50 border border-gray-300 rounded-lg py-3 px-4 shadow-sm focus:bg-white focus:border-green-500 focus:ring-green-500 transition-all" id="district" name="district">
                <option value="">Pilih Kecamatan</option>
            </select>
        </div>

        <div>
            <label for="village" class="block text-sm font-medium text-gray-700 mb-2">Kelurahan<span class="text-red-500 ml-1">*</span></label>
            <select class="w-full bg-gray-50 border border-gray-300 rounded-lg py-3 px-4 shadow-sm focus:bg-white focus:border-green-500 focus:ring-green-500 transition-all" id="village" name="village">
                <option value="">Pilih Kelurahan</option>
            </select>
        </div>
    </div>

    <div class="mb-5">
        <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">Kode Pos<span class="text-red-500 ml-1">*</span></label>
        <input type="text" class="w-full bg-gray-50 border border-gray-300 rounded-lg py-3 px-4 shadow-sm focus:bg-white focus:border-green-500 focus:ring-green-500 transition-all @error('postal_code') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
            id="postal_code" name="postal_code" value="{{ old('postal_code', $muzakki->postal_code) }}" maxlength="5">
        @error('postal_code')
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-5">
        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Alamat<span class="text-red-500 ml-1">*</span></label>
        <textarea class="w-full bg-gray-50 border border-gray-300 rounded-lg py-3 px-4 shadow-sm focus:bg-white focus:border-green-500 focus:ring-green-500 transition-all @error('address') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
            id="address" name="address" rows="3">{{ old('address', $muzakki->address) }}</textarea>
        @error('address')
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
        @enderror
    </div>
</div>
