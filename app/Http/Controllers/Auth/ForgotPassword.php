<?php

namespace app\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ForgotPasswordOtpMail;
use App\Models\User;
use App\Models\UserOtp;
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
        $request->validate(['emp_id' => 'required|exists:App\Models\User,emp_id',]);

        // Get details of user
        $user = User::where('emp_id', $request->emp_id)->first();
        // Generate OTP and send email
        $this->generateOtp($user);

        // Response
        return view('auth/forgot_user_details', ['user' => $user]);
    }

    /**
     * OTP generate and send
     */
    public function generateOtp($user)
    {
        // Generate, store and send OTP to the registered mobile number
        $reg_otp = mt_rand(100000, 999999);
        // Invalidate current active OTP records
        $update = UserOtp::where('user_id', $user->id)->where('verify_status', 0)->update(['verify_status' => 2]);
        // Store OTP
        $store_otp = UserOtp::create([
            'user_id' => $user->id,
            'otp' => $reg_otp,
            'verify_status' => 0,
        ]);
        // Send OTP to email
        $mail_data = [
            'name' => $user->first_name . ' ' . $user->last_name,
            'otp' => $reg_otp,
        ];
        Mail::to($user->email)->send(new ForgotPasswordOtpMail($mail_data));
    }

    /**
     * User OTP validation
     */
    public function validateUserOtp(Request $request, $id)
    {
        // Validation
        $request->validate([
            'reg_otp' => [
                'required',
                'numeric',
                'digits:6',
                Rule::exists('adm_user_otp', 'otp')->where(function (Builder $query) use($id) {
                    $query->where('user_id', $id);
                    $query->where('verify_status', 0);
                }),
            ]
        ]);

        // Update OTP table
        $otp_update = UserOtp::where('user_id', $request->id)->where('verify_status', 0)->update([
            'verify_status' => 1,
            'veified_at' => now()
        ]);

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
        $user_source_id = $request->session()->pull('forgot_user');

        // Update password with flash data
        $update_user = User::where('id', $id)->update([
            'password' => Hash::make($request->password),
        ]);
        // Response
        return redirect('login')->with('status', 'Your have successfully regenerated your password!');
    }
}