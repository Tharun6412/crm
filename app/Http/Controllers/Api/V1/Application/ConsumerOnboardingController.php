<?php

namespace App\Http\Controllers\Api\V1\Application;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DocumentCentre\DocumentUpload;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerDocument;
use App\Models\Consumer\ConsumerMeter;
use App\Models\Consumer\ConsumersStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        if($id <= 0) {
            return response()->json(['message' => 'Onboarding activity started']);
        }else {
            $request->validate([
                'notes' => 'required|max:255',
                'status' => 'required',
            ]);
            // Consumer Status History 3= Accept, 9=Reject
            if($request->status == 1) {
                $con_status = 3;
                $status_val = "accepted";
            }else {
                $con_status = 9;
                $status_val = "rejected";
            }
            // Consumer Update
            Consumer::where('id', $id)->update([
                'status_id' => $con_status,
                'updated_by' => Auth::id(),
            ]);
            // Status History
            ConsumersStatus::create([
                'consumer_id' => $id,
                'status_id' => $con_status,
                'notes' => $request->notes,
                'created_by' => Auth::id(),
            ]);
            // Response
            return response()->json(['success' => 'Consumer status updated Successfully!'], 200);
        }
    }

    /**
     * Accept -> Execution
     */
    public function execution(Request $request, $id)
    {
        $request->validate([
            'meter_no' => 'required|unique:cns_consumer_meters,meter_no',
            'meter_reading' => 'required|numeric',
            'notes' => 'required',
        ]);
        // Meter Images Upload
         // Documents Data Preparation
        $documents_bulk = DocumentUpload::uploadBulk($request, 'domestic');
        if($request->has('dc_file_list')) {
            foreach($request->dc_file_list as $key => $doc_type) {
                $add_consumer_document = ConsumerDocument::create([
                    'consumer_id' => $id,
                    'status_id' => 4,
                    'doc_type_id' => 5,
                    'file_id' => $documents_bulk['file_list'][$key]['file_id'],
                ]);
            }
        }
        // Consumer Meter
        ConsumerMeter::create([
            'consumer_id' => $id,
            'meter_no' => $request->meter_no,
            'initial_reading' => $request->meter_reading,
            'install_date' => Carbon::now(),
            'install_by' => Auth::id(),
            'status' => 1,
            'created_by' => Auth::id(),
        ]);
        // 4 = Execution
        Consumer::where('id', $id)->update([
            'status_id' => 4,
            'updated_by' => Auth::id(),
        ]);
        // Status History
        ConsumersStatus::create([
            'consumer_id' => $id,
            'status_id' => 4,
            'notes' => $request->notes,
            'created_by' => Auth::id(),
        ]);
        // Response
        return response()->json(['success' => 'Consumer executed successfully!'], 500);
    }

    /**
     * Execute -> HSC
     */
    public function hscConnect(Request $request, $id)
    {
        $request->validate([
            'notes' => 'required|max:255',
        ]);
        $doc_upload = DocumentUpload::upload($request, 'domestic');
        //HSC Image Upload
        ConsumerDocument::create([
            'consumer_id' => $id,
            'status_id' => 5,
            'doc_type_id' => 6,
            'file_id' => $doc_upload['file_id'],
        ]);
        // 5 = HSC
        Consumer::where('id', $id)->update([
            'status_id' => 5,
            'updated_by' => Auth::id(),
        ]);
        // Consumer Status History
        ConsumersStatus::create([
            'consumer_id' => $id,
            'status_id' => 5,
            'notes' => $request->notes,
            'created_by' => Auth::id(),
        ]);
        // Response
        return response()->json(['success' => 'Consumer HSC successfully completed!'], 200);
    }
    /**
     * HSC -> Activate
     */
    public function activate(Request $request, $id)
    {
        // Validation
        $request->validate([
            'notes' => 'required|max:255',
        ]);

        // 6 = Activation
        Consumer::where('id', $id)->update([
            'status_id' => 6,
            'updated_by' => Auth::id(),
        ]);
        // Status History
        ConsumersStatus::create([
            'consumer_id' => $id,
            'status_id' => 6,
            'notes' => $request->notes,
            'created_by' => Auth::id(),
        ]);
        // Response
        return response()->json(['success' => 'Consumer activated successfully!'], 200);
    }
}