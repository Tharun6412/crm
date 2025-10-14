<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserRegistrationRequest;
use App\Mail\RegisterOtpMail;
use App\Models\User;
use App\Models\UserSource;
use App\Models\UserSourceOtp;
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
        // return view('auth.registration');
        /* if(session()->has('reg_user')) {
            return redirect('generatePassword');
        }
        else {
            return view('auth.self_registration');
        } */
    }

    /**
     * User verification for self registration
     */
    public function verify(Request $request)
    {
        // Validation
        $request->validate([
            // 'emp_id' => 'required|numeric',
            'emp_id' => [
                'required',
                'numeric', 
                Rule::exists('adm_user_source')->where(function (Builder $query) {
                    $query->where('register_status', 0);
                }),
            ]
        ]);
        
        // Get User source details
        // $user_source = UserSource::where('emp_id', $request->emp_id)->first();
        $this->generateOtp($user_source);

        return view('auth.self_registration_otp', ['user_source' => $user_source]);
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
    public function generateOtp($user_source)
    {
        // Generate, store and send OTP to the registered mobile number
        $reg_otp = mt_rand(100000, 999999);
        // Invalidate current active OTP records
        $update = UserSourceOtp::where('user_source_id', $user_source->id)->where('verify_status', 0)->update(['verify_status' => 2]);
        // Store OTP
        $store_otp = UserSourceOtp::create([
            'user_source_id' => $user_source->id,
            'otp' => $reg_otp,
            'verify_status' => 0,
        ]);
        // Send OTP to email
        $mail_data = [
            'name' => $user_source->first_name . ' ' . $user_source->last_name,
            'otp' => $reg_otp,
        ];
        Mail::to($user_source->email)->send(new RegisterOtpMail($mail_data));
    }

    /**
     * User registration - OTP validation
     */
    public function validateRegisterOtp(Request $request, $id)
    {
        $request->validate([
            'reg_otp' => [
                'required',
                'numeric',
                'digits:6',
                Rule::exists('adm_user_source_otp', 'otp')->where(function (Builder $query) use($id) {
                    $query->where('user_source_id', $id);
                    $query->where('verify_status', 0);
                }),
            ]
        ]);

        // Update OTP table
        $otp_update = UserSourceOtp::where('user_source_id', $request->id)->where('verify_status', 0)->update([
            'verify_status' => 1,
            'veified_at' => now()
        ]);
        // Generate session and redirect or load password creation window
        $request->session()->put('reg_user', $request->id);
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

        // Get and destroy session data
        $user_source_id = $request->session()->pull('reg_user');
        // Get details from user_source
        $user_source_data = UserSource::find($user_source_id);
        // Insert into users table
        $user = User::create([
            'first_name' => $user_source_data->first_name,
            'last_name' => $user_source_data->last_name,
            'email' => $user_source_data->email,
            'mobile' => $user_source_data->mobile,
            'gender' => $user_source_data->gender,
            'emp_id' => $user_source_data->emp_id,
            'dob' => $user_source_data->dob,
            'ga_id' => $user_source_data->ga_id,
            'role_id' => $user_source_data->role_id,
            'department_id' => $user_source_data->department_id,
            'status'=> 1,
            'ga_restriction' => 1,
            'password' => Hash::make($request->password),
        ]);
        // update source & source otp table
        $user_source_update = UserSource::where('id', $user_source_id)->update([
            'register_status' => 1,
            'register_date' => now(),
        ]);
        // redirect to login page
        return redirect()->route('login')->with('status', 'You have successfully activated your account in Intranet');
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