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
            'sd_amount' => 'required',
            'consumption' => 'required',
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
            'document_type.0' => 'required',
            'document_type.1' => 'required',
            'document_type.2' => 'nullable',
            'document_type.3' => 'nullable',
            'dc_file_list.0' => 'required|file|max:51200',
            'dc_file_list.1' => 'required|file|max:51200',
            'dc_file_list.2' => 'nullable|file|max:51200',
            'dc_file_list.3' => 'nullable|file|max:51200',
        ];
    }
}