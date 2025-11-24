<?php

namespace App\Http\Requests\Consumer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * Login request handler
 */
class RegistrationValidationRequest extends FormRequest
{
    /**
     * Validation rules
     */
    public function rules(): array
    {
        return [
            'geo_area' => 'required',
            'district' => 'required',
            'charge_area' => 'required',
            'title' => 'required',
            'fname' => 'required',
            'lname' => 'required',
            'aadhar' => 'required',
            'phone' => 'required',
            'pincode' => 'required',
        ];
    }
}