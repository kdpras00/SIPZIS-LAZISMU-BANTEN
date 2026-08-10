<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreGuestPaymentRequest extends FormRequest
{
    
    public function authorize(): bool
    {
        return true;
    }

    
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^\+?[0-9]{10,15}$/'],
            'email' => 'nullable|email',
            'amount' => 'required|numeric|min:10000',
            'payment_method' => 'required|string',
            'program_id' => 'nullable|exists:programs,id',
            'campaign_id' => 'nullable|exists:campaigns,id',
            'notes' => 'nullable|string|max:500',
            'is_anonymous' => 'nullable|boolean',
        ];
    }
    
    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'phone.regex' => 'Nomor telepon harus berupa 10 hingga 15 digit angka.',
            'amount.min' => 'Nominal donasi minimal Rp 10.000.',
        ];
    }
}
