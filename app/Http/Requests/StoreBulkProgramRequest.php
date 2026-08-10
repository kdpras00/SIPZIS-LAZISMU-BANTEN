<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBulkProgramRequest extends FormRequest
{
    
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if ($this->has('programs') && is_array($this->programs)) {
            $programs = $this->programs;
            foreach ($programs as $index => $programData) {
                if (isset($programData['target_amount'])) {
                    $cleanedAmount = str_replace(['.', ','], '', $programData['target_amount']);
                    if (is_numeric($cleanedAmount)) {
                        $programs[$index]['target_amount'] = (float) $cleanedAmount;
                    }
                }
            }
            $this->merge(['programs' => $programs]);
        }
    }

    
    public function rules(): array
    {
        return [
            'programs' => 'required|array',
            'programs.*.name' => 'required|string|max:255',
            'programs.*.category' => 'required|string|in:zakat,infaq,shadaqah,pendidikan,kesehatan,ekonomi,sosial-dakwah,kemanusiaan,lingkungan',
            'programs.*.target_amount' => 'nullable|numeric|min:0',
            'programs.*.status' => 'required|in:active,inactive',
        ];
    }
}
