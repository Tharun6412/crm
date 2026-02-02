<?php

namespace App\Http\Requests\Consumer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * Idnustrial Form request handler
 */
class IndustrialValidationRequest extends FormRequest
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
            'pan' => 'nullable|alpha_num|size:10',
            'connection_type' => 'required',
            'scheme_id' => 'required',
            'name' => 'required|regex:/^[A-Za-z ]+$/',
            'phone' => 'required|numeric|digits:10',
            'phone_alt' => 'nullable|numeric|digits:10',
            'pincode' => 'required|numeric|digits:6',
            'firm_type_id' => 'required',
            'gst' => 'required',
            'document_type.*' => 'required',
        ];
    }
}