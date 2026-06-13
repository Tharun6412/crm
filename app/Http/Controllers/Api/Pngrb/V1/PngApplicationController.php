<?php
namespace App\Http\Controllers\Api\Pngrb\V1;

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
     * @return object $response JSON HTTP_STATUS
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
            'applicantInfo.mobileNumber' => 'required|digits:10',
            // pngAddress
            'pngAddress.houseNo' => 'required',
            'pngAddress.area' => 'required',
            'pngAddress.city' => 'required',
            'pngAddress.district' => 'required',
            'pngAddress.state' => 'required',
            'pngAddress.pincode' => 'required|digits:6',
            'pngAddress.premiseType' => 'required',
            'pngAddress.latitude' => 'required|numeric',
            'pngAddress.longitude' => 'required|numeric',
        ]);

        // Custom validation error response
        if ($validator->fails()) {
            // Custom error message
            $error_data = [];
            foreach ($validator->errors()->messages() as $field => $messages) {
                foreach ($messages as $message) {
                    $error_data[] = [
                        'field'   => $field,
                        'message' => $message,
                    ];
                }
            }
            return response()->json([
                'success' => false,
                'statusCode' => 400,
                'message' => 'Validation failed',
                'errors' => $error_data,
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
                'father_spouse' => $request->applicantInfo['father_spouse'],
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
                'occupancyType' => $request->pngAddress['occupancyType'],
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

        // Update Log with API details
        ApiLogger::info('pngrb-unified-portal', 'png-application', 'Application Received', [
            $request->all(),
            'httpCode' => $httpCode,
            'message' => $message,
        ]);

        // Response
        return response()->json([
            'success' => true,
            'statusCode' => $httpCode,
            'message' => $message,
            'data' => [
                'applicationNumber' => $request->applicationNumber,
                'cgdId' => $request->cgdId,
                'gaId' => $request->gaId,
                'status' => $request->status,
                'ekycStatus' => $request->ekycStatus,
                'submittedAt' => Carbon::now()->format('Y-m-d\TH:i:s\Z'),
                'referenceId' => 'MCGDPL-REF-ID',
                'portalRedirectUrl' => 'https://www.meghagas.com',
            ]
        ], $httpCode);
    }

    /**
     * Update PNG Application status from Unified Portal
     * 
     * @param object $request JSON Object
     * 
     * @return object response JSON
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'applicationNumber' => 'required',
            'applicationStatus' => 'required',
            'updatedBy' => 'required',
            'updatedAt' => 'required',
        ]);
        // Custom validation error response
        if ($validator->fails()) {
            // Custom error message
            $error_data = [];
            foreach ($validator->errors()->messages() as $field => $messages) {
                foreach ($messages as $message) {
                    $error_data[] = [
                        'field'   => $field,
                        'message' => $message,
                    ];
                }
            }
            // Response
            return response()->json([
                'success' => false,
                'statusCode' => 400,
                'message' => 'Validation failed',
                'errors' => $error_data
            ], 400);
        }

        // Check Applicaion and status
        $application = PngrbApplications::where('applicationNumber', $request->applicationNumber)
            ->whereNull('applicationStatus')->first();
        if($application) {
            // Update Application status
            $application->applicationStatus = $request->applicationStatus;
            $application->statusRemarks = $request->statusRemarks;
            $application->updatedBy = $request->updatedBy;
            $application->updatedAt = $request->updatedAt;
            $application->reviewedDocuments = json_encode($request->reviewedDocuments);
            $application->save();
            // 
            $status = true;
            $httpCode = 200;
            $message = 'Application updated successfully';
            $data = [
                'applicationNumber' => $request->applicationNumber,
                'applicationStatus' => "APPROVED",
                'updatedAt' => Carbon::now()->format('Y-m-d\TH:i:s\Z'),
                'updatedBy' => $application->updatedBy
            ];

            // Update Log with API details
            ApiLogger::info('pngrb-unified-portal', 'png-application', 'Application Update', [
                $request->all(),
                'httpCode' => $httpCode,
                'message' => $message,
            ]);

            // Response
            return response()->json([
                'success' => $status,
                'statusCode' => $httpCode,
                'message' => $message,
                'data' => $data
            ], $httpCode);
        }
        else {
            // Application status is already updated
            $status = false;
            $httpCode = 404;
            $message = 'Not found';
            $errors = [['field' => 'applicationNumber', 'message' => 'No application exists for the provided applicationNumber']];
            
            // Update Log with API details
            ApiLogger::info('pngrb-unified-portal', 'png-application', 'Application Update', [
                $request->all(),
                'httpCode' => $httpCode,
                'message' => $message,
            ]);
    
            // Response
            return response()->json([
                'success' => $status,
                'statusCode' => $httpCode,
                'message' => $message,
                'errors' => $errors
            ], $httpCode);
        }
    }
}