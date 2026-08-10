<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCampaignRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'program_category' => 'required|string|in:zakat,infaq,shadaqah,pendidikan,kesehatan,ekonomi,sosial-dakwah,kemanusiaan,lingkungan',
            'program_id' => 'nullable|exists:programs,id',
            'target_amount' => 'required|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published,completed,cancelled',
            'end_date' => 'nullable|date|after:today'
        ];
    }
}
