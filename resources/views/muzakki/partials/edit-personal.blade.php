<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-6">Data Diri</h3>

    <div class="mb-5">
        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama<span class="text-red-500 ml-1">*</span></label>
        <input type="text" class="w-full bg-gray-50 border border-gray-300 rounded-lg py-3 px-4 shadow-sm focus:bg-white focus:border-green-500 focus:ring-green-500 transition-all @error('name') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
            id="name" name="name" value="{{ old('name', $muzakki->name) }}" required>
        @error('name')
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-5">
        <label for="campaign_url" class="block text-sm font-medium text-gray-700 mb-2">URL List Campaign</label>
        <div class="relative rounded-md shadow-sm">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="bi bi-link-45deg text-gray-400 text-lg"></i>
            </div>
            <input type="url" class="pl-10 w-full bg-gray-100 border border-gray-300 rounded-lg py-3 px-4 text-gray-500 cursor-not-allowed shadow-sm focus:border-gray-300 focus:ring-0"
                id="campaign_url" name="campaign_url" value="{{ old('campaign_url', $muzakki->campaign_url) }}" readonly>
        </div>
        @error('campaign_url')
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-5">
        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email<span class="text-red-500 ml-1">*</span></label>
        <input type="email" class="w-full bg-gray-100 border border-gray-300 rounded-lg py-3 px-4 shadow-sm text-gray-500 cursor-not-allowed focus:border-gray-300 focus:ring-0"
            id="email" name="email" value="{{ old('email', $muzakki->email) }}" readonly>
        @error('email')
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-5">
        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
            No. Telepon<span class="text-red-500 ml-1">*</span>
        </label>

        <div class="flex items-center gap-3">
            <div class="flex-1">
                <input type="tel" id="phone" name="phone" 
                    class="w-full {{ $muzakki->phone_verified ? 'bg-gray-100 text-gray-500 cursor-not-allowed' : 'bg-gray-50' }} border border-gray-300 rounded-lg py-3 pr-4 shadow-sm focus:bg-white focus:border-green-500 focus:ring-green-500 transition-all @error('phone') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                    value="{{ old('phone', preg_replace('/^\+62|^62|^0/', '', $muzakki->phone ?? '')) }}"
                    placeholder="8xxxxxxxxxx"
                    {{ $muzakki->phone_verified ? 'readonly' : '' }}>
            </div>

            @if(!$muzakki->phone_verified)
            <button type="button" class="px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors shadow-sm flex items-center whitespace-nowrap"
                id="verifyPhoneBtn">
                <span id="verifyButtonText">Verifikasi</span>
            </button>
            @endif
            
            <span id="verifyCheckmark" style="{{ $muzakki->phone_verified ? '' : 'display: none;' }}" class="text-green-600" title="Terverifikasi">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                    class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                    <path
                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                </svg>
            </span>
        </div>

        @if (!$muzakki->phone_verified)
        <div class="mt-3 p-3 bg-yellow-50 border-l-4 border-yellow-400 text-yellow-700 text-sm rounded-r-md flex items-start" id="notVerifiedAlert">
            <i class="bi bi-exclamation-triangle mr-2 mt-0.5"></i>
            <span>Mohon verifikasi nomor telepon Anda</span>
        </div>
        @else
        <div class="mt-3 p-3 bg-green-50 border-l-4 border-green-400 text-green-700 text-sm rounded-r-md flex items-start" id="verifiedAlert">
            <i class="bi bi-check-circle-fill mr-2 mt-0.5"></i>
            <span>Nomor telepon sudah diverifikasi</span>
        </div>
        @endif

        @error('phone')
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-5">
        <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">Jenis kelamin<span class="text-red-500 ml-1">*</span></label>
        <select class="w-full bg-gray-50 border border-gray-300 rounded-lg py-3 px-4 shadow-sm focus:bg-white focus:border-green-500 focus:ring-green-500 transition-all @error('gender') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror" id="gender"
            name="gender" required>
            <option value="">----------</option>
            <option value="male" {{ old('gender', $muzakki->gender) == 'male' ? 'selected' : '' }}>Laki-laki</option>
            <option value="female" {{ old('gender', $muzakki->gender) == 'female' ? 'selected' : '' }}>Perempuan</option>
        </select>
        @error('gender')
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-5">
        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal lahir<span class="text-red-500 ml-1">*</span></label>
        <div class="grid grid-cols-3 gap-4">
            <div>
                <select class="w-full bg-gray-50 border border-gray-300 rounded-lg py-3 px-4 shadow-sm focus:bg-white focus:border-green-500 focus:ring-green-500 transition-all" name="birth_day">
                    <option value="">Hari</option>
                    @for ($i = 1; $i <= 31; $i++)
                    <option value="{{ $i }}" {{ old('birth_day', $muzakki->date_of_birth ? $muzakki->date_of_birth->day : '') == $i ? 'selected' : '' }}>
                        {{ $i }}
                    </option>
                    @endfor
                </select>
            </div>
            <div>
                <select class="w-full bg-gray-50 border border-gray-300 rounded-lg py-3 px-4 shadow-sm focus:bg-white focus:border-green-500 focus:ring-green-500 transition-all" name="birth_month">
                    <option value="">Bulan</option>
                    @php
                    $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                    @endphp
                    @foreach ($months as $index => $month)
                    <option value="{{ $index + 1 }}" {{ old('birth_month', $muzakki->date_of_birth ? $muzakki->date_of_birth->month : '') == $index + 1 ? 'selected' : '' }}>
                        {{ $month }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <select class="w-full bg-gray-50 border border-gray-300 rounded-lg py-3 px-4 shadow-sm focus:bg-white focus:border-green-500 focus:ring-green-500 transition-all" name="birth_year">
                    <option value="">Tahun</option>
                    @for ($i = date('Y'); $i >= 1940; $i--)
                    <option value="{{ $i }}" {{ old('birth_year', $muzakki->date_of_birth ? $muzakki->date_of_birth->year : '') == $i ? 'selected' : '' }}>
                        {{ $i }}
                    </option>
                    @endfor
                </select>
            </div>
        </div>
    </div>

    <div class="mb-5">
        <label for="occupation" class="block text-sm font-medium text-gray-700 mb-2">Profesi<span class="text-red-500 ml-1">*</span></label>
        <select class="w-full bg-gray-50 border border-gray-300 rounded-lg py-3 px-4 shadow-sm focus:bg-white focus:border-green-500 focus:ring-green-500 transition-all @error('occupation') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
            id="occupation" name="occupation">
            <option value="">Pilih Profesi</option>
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
        @error('occupation')
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-0">
        <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">Biodata<span class="text-red-500 ml-1">*</span></label>
        <div class="border border-gray-300 rounded-lg p-2 mb-2 bg-gray-50 focus-within:bg-white shadow-sm focus-within:ring-1 focus-within:ring-green-500 focus-within:border-green-500 transition-all">
            <div class="flex gap-1 mb-2 border-b border-gray-100 pb-2">
                <button type="button" class="p-1.5 text-gray-500 hover:bg-gray-100 rounded transition-colors" onclick="formatText('bold')"><i class="bi bi-type-bold"></i></button>
                <button type="button" class="p-1.5 text-gray-500 hover:bg-gray-100 rounded transition-colors" onclick="formatText('italic')"><i class="bi bi-type-italic"></i></button>
                <button type="button" class="p-1.5 text-gray-500 hover:bg-gray-100 rounded transition-colors" onclick="formatText('insertUnorderedList')"><i class="bi bi-list-ul"></i></button>
                <button type="button" class="p-1.5 text-gray-500 hover:bg-gray-100 rounded transition-colors" onclick="formatText('insertOrderedList')"><i class="bi bi-list-ol"></i></button>
                <button type="button" class="p-1.5 text-gray-500 hover:bg-gray-100 rounded transition-colors" onclick="formatText('createLink')"><i class="bi bi-link-45deg"></i></button>
            </div>
            <div contenteditable="true" class="w-full outline-none min-h-[120px] max-h-[300px] overflow-y-auto p-1" id="bio_editor">
                {!! old('bio', $muzakki->bio ?? '') !!}
            </div>
            <textarea name="bio" id="bio" class="hidden">{{ old('bio', $muzakki->bio) }}</textarea>
        </div>
        <p class="text-xs text-gray-500">Dengan membuat cerita yang singkat, kamu akan berkesan pada mendapatkan donasi yang lebih banyak.</p>
        @error('bio')
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
        @enderror
    </div>
</div>
