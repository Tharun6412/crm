<?php

namespace App\Http\Requests\Api\Consumer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * Complaints Validation request handler
 */
class ComplaintValidationRequest extends FormRequest
{
    /**
     * Validation rules
     */
    public function rules(): array
    {
        return [
            'segment_id' => 'required',
            'type_id' => 'required',
            'media_id' => 'required',
            'category_id' => 'required',
            'sub_category_id' => 'required',
            'notes' => 'required|max:225', 
        ];
    }
}