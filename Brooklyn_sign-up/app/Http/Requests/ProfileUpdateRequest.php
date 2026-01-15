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
            
            // Normalize international format: convert 00 prefix to empty (e.g., 0031 -> 31)
            // The 00 prefix is the international dialing prefix and should be removed
            // A valid domestic number starting with 0 (like 0612345678) will only have one 0
            if (preg_match('/^00\d/', $cleaned)) {
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
