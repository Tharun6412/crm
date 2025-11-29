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
            'email' => 'nullable|unique:cns_consumers,email|email:rfc,dns',
            'scheme_id' => 'required',
            'title' => 'required',
            'fname' => 'required|label:FirstName|alpha_dash:ascii',
            'lname' => 'required|alpha_dash:ascii',
            'aadhar' => 'required|digits:12|numeric|unique:cns_consumers,aadhar',
            'phone' => 'required|digits:10|numeric|unique:cns_consumers,phone',
            'phone_alt' => 'nullable|digits:10|unique:cns_consumers,phone_alt',
            'pincode' => 'required|digits:6|numeric',
            'document_type.*' => 'required',
        ];
    }
}