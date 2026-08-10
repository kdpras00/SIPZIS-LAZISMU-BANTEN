<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProgramRequest extends FormRequest
{
    
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if ($this->has('target_amount')) {
            $cleanedAmount = str_replace(['.', ','], '', $this->input('target_amount'));
            if (is_numeric($cleanedAmount)) {
                $this->merge(['target_amount' => (float) $cleanedAmount]);
            }
        }
    }

    
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|in:zakat,infaq,shadaqah,pendidikan,kesehatan,ekonomi,sosial-dakwah,kemanusiaan,lingkungan',
            'target_amount' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}
