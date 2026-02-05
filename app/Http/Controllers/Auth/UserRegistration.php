<?php

namespace App\Http\Controllers\Auth;

use App\Enums\OtpModule;
use App\Enums\OtpPurpose;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserRegistrationRequest;
use App\Mail\RegisterOtpMail;
use App\Mail\User\RegisterOtpMail as UserRegisterOtpMail;
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
use Illuminate\Validation\ValidationException;

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
        // Check email sent counts
        $register_otp_attempt = session('register_otp_attempt');
        // print_r($register_otp_attempt[$id]);
        if(isset($register_otp_attempt[$id]) AND $register_otp_attempt[$id] <= 3) {
            // Get details and send email
            $user = User::find($id);
            $otp = $this->generateOtp($user);

            return response()->json(['success' => 'OTP sent to your email successfully!']);
        }
        else {
            return response()->json(['success' => 'Tried maximum attempts, please try again later!']);
        }
    }

    /**
     * OTP generate and send
     */
    public function generateOtp($user)
    {
        // Generate, store and send OTP to the registered mobile number
        $otp = OtpService::create($user->email, OtpPurpose::REGISTER->value, OtpModule::USER->value);
        
        // Send OTP to email
        $mail_data = [
            'name' => $user->name,
            'otp' => $otp,
        ];
        Mail::to($user->email)->send(new UserRegisterOtpMail($mail_data));

        // Mail sent counter
        $register_otp_attempt = (session()->exists('register_otp_attempt')) ? session()->pull('register_otp_attempt') : [];
        $register_otp_attempt[$user->id] = isset($register_otp_attempt[$user->id]) ? ($register_otp_attempt[$user->id] + 1) : 1;
        session()->put('register_otp_attempt', $register_otp_attempt);

        // Return
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

        // Check OTP and update
        if(OtpService::verify($user->email, OtpPurpose::REGISTER->value, $request->reg_otp, OtpModule::USER->value)) {
            // Generate session and redirect or load password creation window
            $request->session()->put('reg_user', $user->id);
            return response()->json(['status' => 1, 'url' => url('generatePassword')]);
        }
        else {
            throw ValidationException::withMessages(['reg_otp' => 'Invalid OTP, please enter correct OTP.']);
        }
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