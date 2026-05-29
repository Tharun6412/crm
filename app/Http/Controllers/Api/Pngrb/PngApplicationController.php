<?php
namespace App\Http\Controllers\Api\Pngrb;

use App\Helpers\ApiLogger;
use App\Http\Controllers\Controller;
use App\Models\Consumer\PngrbApplications;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PngApplicationController extends Controller
{
    /**
     * Index
     */
    public function index()
    {
        return 'Receive PNG application from PNGRB.';
    }

    /**
     * Receive application data from PNGRB
     * 
     * @param object $request JSON object
     * 
     * @return object HTTP_STATUS
     * 201 Created	Application Submitted Successfully
     * 200 OK	Application Already Exists
     * 400 Bad Request	Validation Error
     * 401 Unauthorized	Invalid or Missing Token
     * 403 Forbidden	Insufficient Permissions
     * 404 Not Found	CGD or GA Not Found
     * 409 Conflict	Duplicate Application
     * 422 Unprocessable Entity	Address Not Serviceable
     * 429 Too Many Requests	Rate Limit Exceeded
     * 500 Internal Server Error	Server Error
     */
    public function store(Request $request)
    {
        // Validation of input request
        $validator = Validator::make($request->all(), [
            'applicationNumber' => 'required',
            'cgdId' => 'required',
            'gaId' => 'required',
            'status' => 'required',
            'ekycStatus' => 'required',
            'serviceabilityStatus' => 'required',
            // applicantInfo
            'applicantInfo.name' => 'required',
            'applicantInfo.mobileNumber' => 'required',
            // pngAddress
            'pngAddress.houseNo' => 'required',
            'pngAddress.area' => 'required',
            'pngAddress.city' => 'required',
            'pngAddress.district' => 'required',
            'pngAddress.state' => 'required',
            'pngAddress.pincode' => 'required',
            'pngAddress.premiseType' => 'required',
            'pngAddress.latitude' => 'required',
            'pngAddress.longitude' => 'required',
        ]);

        // Custom validation error response
        if ($validator->fails()) {
            return response()->json([
                'acknowledged' => true,
                'receivedAt' => Carbon::now()->format('Y-m-d\TH:i:sZ'),
                'message' => $validator->errors()->all(),
            ], 400);
        }
        
        // 1. Check the application exists or not
        $check_application = PngrbApplications::where('applicationNumber', $request->applicationNumber)->get();
        if($check_application->count() == 0) {
            // Insert to PNGRB application
            $new_application_id = PngrbApplications::create([
                'applicationNumber' => $request->applicationNumber,
                'cgdId' => $request->cgdId,
                'gaId' => $request->gaId,
                'status' => $request->status,
                'ekycStatus' => $request->ekycStatus,
                'serviceabilityStatus' => $request->serviceabilityStatus,
                // applicantInfo
                'name' => $request->applicantInfo['name'],
                'mobileNumber' => $request->applicantInfo['mobileNumber'],
                'father_spouse' => $request->applicantInfo['father-spouse'],
                'dob' => $request->applicantInfo['dob'],
                'email' => $request->applicantInfo['email'],
                'whatsapp' => $request->applicantInfo['whatsapp'],
                // pngAddress
                'houseNo' => $request->pngAddress['houseNo'],
                'floor' => $request->pngAddress['floor'],
                'society' => $request->pngAddress['society'],
                'area' => $request->pngAddress['area'],
                'city' => $request->pngAddress['city'],
                'district' => $request->pngAddress['district'],
                'state' => $request->pngAddress['state'],
                'pincode' => $request->pngAddress['pincode'],
                'premiseType' => $request->pngAddress['premiseType'],
                'latitude' => $request->pngAddress['latitude'],
                'longitude' => $request->pngAddress['longitude'],
            ]);

            // 2. Identify Serviceable
            // 422 Unprocessable Entity	Address Not Serviceable

            $message = 'Application Submitted Successfully';
            $httpCode = 201;
            
            // Update response 
            $new_application_id->response_code = $httpCode;
            $new_application_id->response_message = $message;
            $new_application_id->save();
        }
        else {
            // 200 OK Application already exists
            $message = 'Application already exists';
            $httpCode = 200;
        }

        // Log API details
        ApiLogger::info('pngrb-unified-portal', 'png-application', 'Application received', [
            $request->all(),
            'httpCode' => $httpCode,
            'message' => $message,
        ]);

        // Response
        return response()->json([
            'acknowledged' => true,
            'receivedAt' => Carbon::now()->format('Y-m-d\TH:i:s\Z'),
            'message' => $message,
        ], $httpCode);
    }
}