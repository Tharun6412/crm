<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\Password;

/**
 * Login request handler
 */
class ChangePasswordRequest extends FormRequest
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
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', Password::min(8)->numbers()->mixedCase()],
        ];
    }

    /**
     * Validate current password
     */
    public function validateCurrentPassword()
    {
        // Validate current password with current password
        if(!Hash::check($this->current_password, $this->user()->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Provided passwords does not match with out records.',
            ]);
        }

        // Validate new password with current password
        if(Hash::check($this->password, $this->user()->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'New password should be different from current password.',
            ]);
        }
    }

    /**
     * Update password
     */
    public function updatePassword()
    {
        // Validate current password
        $this->validateCurrentPassword();

        // Update password
        $this->user()->fill([
            'password' => Hash::make($this->password)
        ])->save();
    }
}