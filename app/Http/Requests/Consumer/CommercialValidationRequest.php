<?php

namespace App\Http\Requests\Consumer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * Commecial Form request handler
 */
class CommercialValidationRequest extends FormRequest
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
            'name' => 'required|regex:/^[A-Za-z ]+$/',
            'phone' => 'required|numeric|digits:10',
            'phone_alt' => 'nullable|numeric|digits:10',
            'pan' => 'nullable|alpha_num|size:10',
            'gst' => 'nullable|alpha_num',
            'hours' => 'nullable|numeric',
            'dcq' => 'nullable|numeric',
            'pincode' => 'required|numeric|digits:6',
            'business_type_id' => 'required',
            'owner_phone' => 'nullable|numeric|digits:10',
            'document_type.*' => 'required',
        ];
    }
}