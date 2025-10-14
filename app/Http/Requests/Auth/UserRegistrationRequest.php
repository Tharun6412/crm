<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UserRegistrationRequest extends FormRequest
{
    /**
     * Rules
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => 'required|email|unique:App\Models\User,email',
            'emp_id' => 'required|unique:App\Models\User,emp_id',
            'password' => 'required|confirmed'
        ];
    }
}