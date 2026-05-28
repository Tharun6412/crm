<?php

namespace App\Http\Controllers\Api\V1\Application;

use App\Enums\AwsPath;
use App\Enums\ConsumerStatus as EnumsConsumerStatus;
use App\Enums\DocumentType;
use App\Enums\MeterStatus;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Master\DocumentCentre\DocumentUpload;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerDocument;
use App\Models\Consumer\ConsumerMeter;
use App\Models\Consumer\ConsumerStatus;
use App\Notifications\Consumer\AcceptSmsNotification;
use App\Notifications\Consumer\ActivateSmsNotification;
use App\Notifications\Consumer\ExecuteSmsNotification;
use App\Notifications\Consumer\HscSmsNotification;
use App\Notifications\Consumer\RejectSmsNotification;
use App\Services\SmsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * Consumer Onboarding Process
 * 2 => Register
 * 3 => Accept
 * 4 => Execute
 * 5 => HSC
 * 6 => Activate
 * 7 => TD
 * 8 => PD
 * 9 => Reject
 */
class ConsumerOnboardingController extends Controller
{
    /**
     * Registered -> Accept/Reject
     */
    public function acceptance(Request $request, $id)
    {
        $consumer = Consumer::find($id);
        if($id <= 0) {
            return response()->json(['message' => 'Onboarding activity started']);
        }else {
            $request->validate([
                'notes' => 'required|max:255',
                'status' => 'required',
            ]);
            // Consumer Status History 3= Accept, 9=Reject
            if($request->status == 1) {
                $con_status = EnumsConsumerStatus::ACCEPT->value;
                $status_val = "accepted";
            }else {
                $con_status = EnumsConsumerStatus::REJECT->value;
                $status_val = "rejected";
            }
            // Consumer Update
            $consumer->update([
                'status_id' => $con_status,
                'updated_by' => Auth::id(),
            ]);
            // Status History
            ConsumerStatus::create([
                'consumer_id' => $id,
                'lat' => $request->lat,
                'lng' => $request->lng,
                'status_id' => $con_status,
                'notes' => $request->notes,
                'created_by' => Auth::id(),
            ]);
            // Sms Integration
            if($con_status == EnumsConsumerStatus::ACCEPT->value) {
                $sms_response = SmsService::dispatch($consumer, new AcceptSmsNotification(['crn' => $consumer->crn]));
            }else {
                $sms_response = SmsService::dispatch($consumer, new RejectSmsNotification(['crn' => $consumer->crn]));
            }
            // Response
            return response()->json(['success' => 'Consumer status updated Successfully!'], 200);
        }
    }

    /**
     * Accept -> Execution
     */
    public function execution(Request $request, $id)
    {
        // Get Consumer Details
        $consumer = Consumer::find($id);
        if($consumer->id == $id AND $consumer->status_id == EnumsConsumerStatus::EXECUTE->value AND $consumer->activeMeter?->status == MeterStatus::ACTIVE->value) {
            // return response()->json(['success', 'Consumer Status already exists'], 200);
        }else {
            if($consumer) {
                // Validation
                $request->validate([
                    'meter_no' => ['required',
                        Rule::unique('cns_consumer_meters', 'meter_no')->where(function($q) {
                            $q->where('status', 1);
                        }),
                    ],
                    'meter_serial_no' => [
                        Rule::requiredIf($consumer->connection_type_id == 2), 
                        'nullable',
                        Rule::unique('cns_consumer_meters', 'meter_serial_no')->where(function($q) {
                            $q->where('status', 1);
                        }),
                    ],
                    'meter_reading' => 'required|numeric',
                    'notes' => 'required',
                ]);
                // Meter Images Upload
                 // Documents Data Preparation
                $documents_bulk = DocumentUpload::uploadBulk($request, AwsPath::EXECUTION->value);
                if($request->has('dc_file_list')) {
                    $add_consumer_document = ConsumerDocument::create([
                        'consumer_id' => $id,
                        'status_id' => EnumsConsumerStatus::EXECUTE->value,
                        'doc_type_id' => 5,
                        'file_id' => $documents_bulk['file_list'][0]['file_id'],
                    ]);
                }
                // Consumer Meter
                ConsumerMeter::create([
                    'consumer_id' => $id,
                    'file_id' => $documents_bulk['file_list'][1]['file_id'],
                    'meter_no' => $request->meter_no,
                    'meter_serial_no' => $request->meter_serial_no,
                    'initial_reading' => $request->meter_reading,
                    'install_date' => Carbon::now(),
                    'install_by' => Auth::id(),
                    'status' => MeterStatus::ACTIVE->value,
                    'created_by' => Auth::id(),
                ]);
                // 4 = Execution
                $consumer->update([
                    'status_id' => EnumsConsumerStatus::EXECUTE->value,
                    'updated_by' => Auth::id(),
                ]);
                // Status History
                ConsumerStatus::create([
                    'consumer_id' => $id,
                    'lat' => $request->lat,
                    'lng' => $request->lng,
                    'status_id' => EnumsConsumerStatus::EXECUTE->value,
                    'notes' => $request->notes,
                    'created_by' => Auth::id(),
                ]);
            }
        }
        // Sms Notification
        $sms_response = SmsService::dispatch($consumer, new ExecuteSmsNotification(['crn' => $consumer->crn]));
        // Response
        return response()->json(['success' => 'Consumer executed successfully!'], 200);
    }

    /**
     * Execute -> HSC
     */
    public function hscConnect(Request $request, $id)
    {
        $consumer = Consumer::find($id);
        if($consumer->id == $id AND $consumer->status_id == EnumsConsumerStatus::HSC->value ) {
            // Already Exists
        }else {
            // Request Validation
            $request->validate([
                'notes' => 'required|max:255',
            ]);
            $doc_upload = DocumentUpload::upload($request, AwsPath::HSC->value);
            //HSC Image Upload
            ConsumerDocument::create([
                'consumer_id' => $id,
                'status_id' => EnumsConsumerStatus::HSC->value,
                'doc_type_id' => 6,
                'file_id' => $doc_upload['file_id'],
            ]);
            // 5 = HSC
            $consumer->update([
                'status_id' => 5,
                'updated_by' => Auth::id(),
            ]);
            // Consumer Status History
            ConsumerStatus::create([
                'consumer_id' => $id,
                'lat' => $request->lat,
                'lng' => $request->lng,
                'status_id' => EnumsConsumerStatus::HSC->value,
                'notes' => $request->notes,
                'created_by' => Auth::id(),
            ]);
        }
        // Sms Notification
        $sms_response = SmsService::dispatch($consumer, new HscSmsNotification(['crn' => $consumer->crn]));
        // Response
        return response()->json(['success' => 'Consumer HSC successfully completed!'], 200);
    }
    /**
     * HSC -> Activate
     */
    public function activate(Request $request, $id)
    {
        $consumer = Consumer::find($id);
        if($consumer->id == $id AND $consumer->status_id == EnumsConsumerStatus::ACTIVATE->value) {
            // Already Exists
        }else {
            // Validation
            $request->validate([
                'notes' => 'required|max:255',
            ]);
            //Check If Document has been uploaded [optional] 
            if($request->has('dc_file')) {
                $doc_upload = DocumentUpload::upload($request, AwsPath::ACTIVATION->value);
                //Activate Image Upload
                ConsumerDocument::create([
                    'consumer_id' => $id,
                    'status_id' => EnumsConsumerStatus::ACTIVATE->value,
                    'doc_type_id' => DocumentType::ACTIVATION_IMAGE->value,
                    'file_id' => $doc_upload['file_id'],
                ]);
            }
            // 6 = Activation
            $consumer->update([
                'status_id' => EnumsConsumerStatus::ACTIVATE->value,
                'activation_date' => now()->toDateTimeString(),
                'updated_by' => Auth::id(),
            ]);
            // Status History
            ConsumerStatus::create([
                'consumer_id' => $id,
                'lat' => $request->lat,
                'lng' => $request->lng,
                'status_id' => EnumsConsumerStatus::ACTIVATE->value,
                'notes' => $request->notes,
                'created_by' => Auth::id(),
            ]);
        }
        // Sms Notification
        $sms_response = SmsService::dispatch($consumer, new ActivateSmsNotification(['crn' => $consumer->crn]));
        // Response
        return response()->json(['success' => 'Consumer activated successfully!'], 200);
    }
}