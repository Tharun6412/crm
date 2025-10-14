<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * Login request handler
 */
class AuthenticationRequest extends FormRequest
{
    /**
     * Authorize submission
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules
     */
    public function rules(): array
    {
        return [
            // 'email' => 'required|email',
            'emp_id' => 'required',
            'password' => 'required',
        ];
    }

    /**
     * Authentication
     */
    public function authenticate()
    {
        // Authentication with database table with status active
        // if(!Auth::attempt($this->only('emp_id', 'password'))) {
        if(!Auth::attempt(['emp_id' => $this->emp_id, 'password' => $this->password, 'status' => 1])) {
            // Set validation message
            throw ValidationException::withMessages([
                'emp_id' => 'Invalid Employee Id or password!'
            ]);
        }
    }
}