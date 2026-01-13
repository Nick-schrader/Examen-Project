<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('telefoon')) {
            // Strip formatting characters (spaces, dashes, parentheses, plus signs)
            // to normalize the phone number before validation and storage
            $cleaned = preg_replace('/[\s\-\(\)\+]/', '', $this->telefoon);
            
            // Normalize international format: convert 00XX prefix to XX (e.g., 0031 -> 31)
            if (str_starts_with($cleaned, '00')) {
                $cleaned = substr($cleaned, 2); // Remove leading '00'
            }
            
            $this->merge([
                'telefoon' => $cleaned,
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'telefoon' => [
                'required',
                'string',
                'regex:/^[0-9]{9,15}$/', // Validates cleaned phone number (9-15 digits)
            ],
        ];
    }
}
