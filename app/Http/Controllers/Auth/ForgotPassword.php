<?php

namespace app\Http\Controllers\Auth;

use App\Enums\OtpModule;
use App\Enums\OtpPurpose;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Mail\ForgotPasswordOtpMail;
use App\Models\Admin\User;
use App\Models\Admin\UserStatusHistory;
use App\Models\UserOtp;
use App\Services\OtpService;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ForgotPassword extends Controller
{
    /**
     * Index
     */
    public function index()
    {
        // Response
        return view('auth/forgot_password');
    }

    /**
     * Verify user
     */
    public function userVerify(Request $request)
    {
        // Validation
        $request->validate(['emp_id' => 'required']);

        // Get details of user
        $user = User::where(['emp_id' => $request->emp_id, 'status_id' => UserStatus::ACTIVE->value])->first();
        if(!$user)
            abort(422, 'Invalid Emp Id / Employee disabled');
        // Generate OTP and send email
        $otp = $this->generateOtp($user);

        // Response
        return view('auth/forgot_user_details', ['user' => $user, 'otp' => $otp]);
    }

    /**
     * OTP generate and send
     */
    public function generateOtp($user)
    {
        // Generate, store and send OTP to the registered mobile number
        $otp = OtpService::create($user->mobile, OtpPurpose::PASSWORD_RESET->value, OtpModule::USER->value);

        // Send OTP to email
        $mail_data = [
            'name' => $user->name,
            'otp' => $otp,
        ];
        // Mail::to($user->email)->send(new ForgotPasswordOtpMail($mail_data));
        return $otp;
    }

    /**
     * User OTP validation
     */
    public function validateUserOtp(Request $request, $id)
    {
        // Validation
        $request->validate([
            'reg_otp' => 'required|numeric|digits:6',
        ]);

        // Get User deatils
        $user = User::find($id);

        // Update OTP table
        $verify_otp = OtpService::verify($user->mobile, OtpPurpose::PASSWORD_RESET->value, $request->reg_otp, OtpModule::USER->value);

        // Generate session and redirect or load password creation window
        $request->session()->put('forgot_user', $request->id);
        return response()->json(['status' => 1, 'url' => url('reGeneratePassword')]);
    }

    /**
     * Re-Generate password
     */
    public function reGeneratePassword(Request $request)
    {
        if($request->session()->has('forgot_user')) {
            return view('auth.regenerate_password');
        }
        else {
            return redirect('forgotPassword');
        }
    }

    /**
     * updatePassword
     */
    public function updatePassword(Request $request, $id)
    {
        // Password validation with password rules
        $request->validate([
            'password' => [
                'required', 
                'confirmed', 
                Password::min(8)->numbers()->mixedCase()
            ],
        ]);
        // Get and destroy session data
        $user_id = $request->session()->pull('forgot_user');

        // Update password with flash data
        $update_user = User::where('id', $id)->update([
            'password' => Hash::make($request->password),
        ]);
        // Create status history record
        UserStatusHistory::create([
            'user_id' => $user_id,
            'status_id' => UserStatus::RSET_PASSWORD->value,
            'created_by' => $user_id,
        ]);

        // Response
        return redirect('login')->with('status', 'Your have successfully regenerated your password!');
    }

    /**
     * Cancel Regenarate
     */
    public function cancelReset(Request $request)
    {
        // Unset session
        $request->session()->forget('forgot_user');
        return redirect('forgotPassword');
    }
}