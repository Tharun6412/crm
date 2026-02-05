{{-- Forgot password OTP mail body --}}
<p>Dear {{ $mailData['name'] }},</p>
<p><strong>{{ $mailData['otp'] }}</strong> is your One Time Password (OTP) to regenarte password for your account in MeghaGas Portal with Megha City Gas Distribution Private Limited. This code is valid for 3 hours. Please enter this OTP to regenerate password, this code is valid for one time use only. Do not share with anyone.</p>
<p>--<br>Best Regards,<br>MeghaGas.</p>
<small>This is an auto-generated e-mail. Please do not reply.</small>