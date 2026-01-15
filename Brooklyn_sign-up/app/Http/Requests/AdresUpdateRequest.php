<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdresUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'straat' => ['required', 'string', 'max:255'],
            'huisnummer' => ['required', 'string', 'max:10'],
            'postcode' => ['required', 'string', 'max:7', 'regex:/^[1-9][0-9]{3}\s?[A-Za-z]{2}$/'],
            'woonplaats' => ['required', 'string', 'max:255'],
        ];
    }
}
