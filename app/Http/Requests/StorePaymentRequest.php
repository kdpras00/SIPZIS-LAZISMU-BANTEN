<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    
    public function authorize(): bool
    {
        $user = $this->user();

        if (!$user) {
            return false;
        }

        if ($user->hasRole('admin')) {
            return true;
        }

        $muzakkiId = $user->muzakki?->id;

        if (!$muzakkiId) {
            return false;
        }

        $idField = $this->input('muzakki_id');
        $routePayment = $this->route('payment');

        if ($routePayment && $routePayment->muzakki_id !== $muzakkiId) {
            abort(403, 'Anda tidak memiliki akses ke pembayaran ini.');
        }

        return $idField === null || (int) $idField === $muzakkiId;
    }

    protected function prepareForValidation()
    {
        if ($this->has('paid_amount')) {
            $cleanedAmount = str_replace(['.', ','], '', $this->input('paid_amount'));
            if (is_numeric($cleanedAmount)) {
                $this->merge(['paid_amount' => (float) $cleanedAmount]);
            }
        }
    }

    
    public function rules(): array
    {
        return [
            'muzakki_id' => 'required|exists:muzakki,id',
            'payment_date' => 'required|date',
            'paid_amount' => 'required|numeric|min:1000',
            'payment_method' => 'required|string',
            'program_id' => 'nullable|exists:programs,id',
            'payment_reference' => 'nullable|string|max:255',
            'status' => 'required|in:pending,completed,cancelled',
            'notes' => 'nullable|string',
        ];
    }
}
