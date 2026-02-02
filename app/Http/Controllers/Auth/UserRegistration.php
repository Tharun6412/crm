<?php

namespace App\Http\Controllers\Auth;

use App\Enums\OtpModule;
use App\Enums\OtpPurpose;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserRegistrationRequest;
use App\Mail\RegisterOtpMail;
use App\Models\Admin\User;
use App\Models\Admin\UserStatusHistory;
use App\Services\OtpService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserRegistration extends Controller
{
    /**
     * Index
     */
    public function index()
    {
        if(session()->has('reg_user')) {
            return redirect('generatePassword');
        }
        else {
            return view('auth.self_registration');
        }
    }

    /**
     * User verification for self registration
     */
    public function verify(Request $request)
    {
        // Validation
        $request->validate([
            'emp_id' => [
                'required',
                'numeric', 
                Rule::exists('users')->where(function (Builder $query) {
                    $query->where('status_id', 3);
                }),
            ]
        ],
        [
            'emp_id' => 'Invalid Emp Id / Already registered.',
        ]);
        
        // Get User details
        $user = User::where('emp_id', $request->emp_id)->first();
        $otp = $this->generateOtp($user);

        return view('auth.self_registration_otp', ['user' => $user, 'otp' => $otp]);
    }

    /**
     * Resend email
     */
    public function resendEmail(Request $request, $id)
    {
        // Get details and send email
        // $user_source = UserSource::find($id);
        // $this->generateOtp($user_source);

        return response()->json(['success' => 'OTP sent to your email successfully!']);
    }

    /**
     * OTP generate and send
     */
    public function generateOtp($user)
    {
        // Generate, store and send OTP to the registered mobile number
        $otp = OtpService::create($user->mobile, OtpPurpose::REGISTER->value, OtpModule::USER->value);

        // Send OTP to email
        $mail_data = [
            'name' => $user->name,
            'otp' => $otp,
        ];
        // Mail::to($user->email)->send(new RegisterOtpMail($mail_data));
        return $otp;
    }

    /**
     * User registration - OTP validation
     */
    public function validateRegisterOtp(Request $request, $id)
    {
        $request->validate([
            'reg_otp' => 'required|numeric|digits:6',
        ]);

        // Get User deatils
        $user = User::find($id);

        // Update OTP table
        $verify_otp = OtpService::verify($user->mobile, OtpPurpose::REGISTER->value, $request->reg_otp, OtpModule::USER->value);
        
        // Generate session and redirect or load password creation window
        $request->session()->put('reg_user', $user->id);
        return response()->json(['status' => 1, 'url' => url('generatePassword')]);
    }
    
    /**
     * Generate password form
     */
    public function generatePassword(Request $request)
    {
        if($request->session()->has('reg_user')) {
            return view('auth.self_registration_password');
        }
        else {
            return redirect()->route('register');
        }
    }

    /**
     * Cancel registration
     */
    public function cancelRegistration(Request $request)
    {
        // Unset session
        $request->session()->forget('reg_user');
        return redirect('register');
    }

    /**
     * Self register - 
     */
    public function storePassword(Request $request)
    {
        // Password validation with password rules
        $request->validate([
            'password' => [
                'required', 
                'confirmed', 
                Password::min(8)->numbers()->mixedCase()
            ],
        ]);

        // Get user_id and destroy session data
        $user_id = $request->session()->pull('reg_user');
        // Get user details
        $user = User::find($user_id);
        // Update Password and status
        $user->password = Hash::make($request->password);
        $user->status_id = UserStatus::ACTIVE->value;
        $user->save();
        // Create status history record
        UserStatusHistory::create([
            'user_id' => $user_id,
            'status_id' => UserStatus::ACTIVE->value,
            'created_by' => $user_id,
        ]);
        
        // redirect to login page
        return redirect()->route('login')->with('status', 'You have successfully activated your account!');
    }

    /**
     * User Registration
     */
    public function store(UserRegistrationRequest $request)
    {
        // Save
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'emp_id' => $request->emp_id,
            'password' => Hash::make($request->password),
        ]);

        // User event
        event(new Registered($user));

        // Login with user
        Auth::login($user);
        
        // Redirect
        return redirect(route('home'));
    }
}