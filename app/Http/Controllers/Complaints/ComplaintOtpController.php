<?php

namespace App\Http\Controllers\Complaints;

use App\Enums\ConsumerStatus;
use App\Enums\OtpPurpose;
use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use App\Models\Service\Otp;
use Illuminate\Http\Request;

class ComplaintOtpController extends Controller
{
    /**
     * Quick Search 
     */
    public function index(Request $request)
    {
        // Get consumers
        if($request->ajax()) {
            if(empty($request->search)) {
                return response()->json(['message' => 'Please enter mobile number'], 422);
            }
            // $otp_details = Otp::
            $otp_details = Otp::where([
                'identifier' => $request->search,
                'purpose' => OtpPurpose::COMPLAINT_CLOSE->value,
                'is_used' => false
            ])->where('expires_at', '>=', now())->latest()->first();
            // Ajax Response
            return view('complaints.otp-search-body', ['otp_details' => $otp_details]);
        }
        // Response
        return view('complaints.otp-search');
    }
}