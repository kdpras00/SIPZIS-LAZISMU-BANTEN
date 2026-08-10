<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDistributionRequest extends FormRequest
{
    
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if ($this->has('amount')) {
            $cleanedAmount = str_replace(['.', ','], '', $this->input('amount'));
            if (is_numeric($cleanedAmount)) {
                $this->merge(['amount' => (float) $cleanedAmount]);
            }
        }
    }

    
    public function rules(): array
    {
        return [
            'mustahik_id' => 'required|exists:mustahik,id',
            'amount' => 'required|numeric|min:0',
            'distribution_type' => 'required|in:cash,goods,voucher,service',
            'goods_description' => 'required_if:distribution_type,goods,service|nullable|string',
            'distribution_date' => 'required|date',
            'notes' => 'nullable|string',
            'program_id' => 'nullable|exists:programs,id',
            'program_slug' => 'nullable|string|exists:programs,slug',
            'program_name' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
        ];
    }
}
