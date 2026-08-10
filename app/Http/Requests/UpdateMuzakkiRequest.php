<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMuzakkiRequest extends FormRequest
{
    
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if ($this->has('new_password')) {
            return []; 
        }

        $muzakkiId = $this->route('muzakki') ? $this->route('muzakki')->id : (auth()->user()->muzakki->id ?? null);

        $rules = [
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s\.\'\`-]+$/'],
            'email' => 'nullable|email|unique:muzakki,email,' . $muzakkiId,
            'phone' => ['nullable', 'string', 'regex:/^[0-9]{10,15}$/'],
            'nik' => ['nullable', 'string', 'regex:/^[0-9]{16}$/', 'unique:muzakki,nik,' . $muzakkiId],
            'gender' => 'required|in:male,female',
            'address' => 'nullable|string',
            'city' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z\s\.\'\`-]+$/'],
            'province' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z\s\.\'\`-]+$/'],
            'district' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z\s\.\'\`-]+$/'],
            'village' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z\s\.\'\`-]+$/'],
            'postal_code' => 'nullable|string|digits:5',
            'occupation' => ['nullable', 'string', 'max:100', 'regex:/^[a-zA-Z\s\.\'\`-]+$/'],
            'date_of_birth' => 'nullable|date',
            'monthly_income' => 'nullable|numeric|min:0',
            'bio' => 'nullable|string',
            'is_active' => 'boolean',
            'country' => 'nullable|string|max:255',
            'campaign_url' => 'nullable|url|max:500',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ktp_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.regex' => 'Nama hanya boleh berisi huruf, spasi, titik, dan tanda petik.',
            'city.regex' => 'Nama kota/kabupaten hanya boleh berisi huruf dan tidak boleh angka.',
            'province.regex' => 'Nama provinsi hanya boleh berisi huruf dan tidak boleh angka.',
            'district.regex' => 'Nama kecamatan hanya boleh berisi huruf dan tidak boleh angka.',
            'village.regex' => 'Nama kelurahan hanya boleh berisi huruf dan tidak boleh angka.',
            'occupation.regex' => 'Nama pekerjaan hanya boleh berisi huruf dan tidak boleh angka.',
            'phone.regex' => 'Nomor telepon harus berupa 10 hingga 15 digit angka.',
            'nik.regex' => 'NIK harus terdiri dari tepat 16 digit angka.',
            'nik.unique' => 'NIK ini sudah terdaftar dalam sistem.',
            'postal_code.digits' => 'Kode pos harus terdiri dari 5 digit angka.',
        ];
    }
}
