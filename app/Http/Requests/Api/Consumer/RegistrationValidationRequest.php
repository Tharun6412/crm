<?php

namespace App\Http\Requests\Api\Consumer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * Consumer Validation request handler
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
            'area' => 'required',
            'email' => 'nullable|email',
            'connection_type' => 'required',
            'scheme_id' => 'required',
            'title' => 'required',
            'fname' => 'required|alpha_dash:ascii',
            'lname' => 'required|alpha_dash:ascii',
            'aadhar' => 'required|numeric|digits:12',
            'phone' => 'required|numeric|digits:10',
            'phone_alt' => 'nullable|numeric|digits:10',
            'pincode' => 'required|numeric|digits:6',
            'owner_phone' => 'nullable|numeric|digits:10',
            'tenant_phone' => 'nullable|numeric|digits:10',
            'document_type.0' => 'required',
            'document_type.1' => 'required',
        ];
    }
}