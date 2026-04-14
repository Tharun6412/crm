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
            'sd_amount' => 'required',
            'consumption' => 'required',
            'name' => 'required|regex:/^[A-Za-z ]+$/',
            'phone' => 'required|numeric|digits:10',
            'phone_alt' => 'nullable|numeric|digits:10',
            'pincode' => 'required|numeric|digits:6',
            'firm_type_id' => 'required',
            'gst' => 'required',
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