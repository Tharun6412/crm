<?php
namespace App\Http\Controllers\Consumer;

use App\Enums\AwsPath;
use App\Enums\ConsumerStatus as EnumsConsumerStatus;
use App\Enums\DocumentType;
use App\Enums\MeterStatus;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Master\DocumentCentre\DocumentUpload;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerDocument;
use App\Models\Consumer\ConsumerStatus;
use App\Models\Consumer\ConsumerMeter;
use App\Notifications\Consumer\ExecuteSmsNotification;
use App\Services\SmsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ExecuteController extends Controller
{
    /**
     * Index method
     */
    public function index(Request $request)
    {
        echo "test method";
    }

    /**
     * Execute consumer form
     */
    public function edit(Request $request, $id) 
    {
        return view('consumers.execute.edit', ['id' => $id]);
    }

    /**
     * Execution State
     * Accepted -> Executed
     */
    public function update(Request $request, $id)
    {
        // Get Consumer Details
        $consumer = Consumer::find($id);
        $request->validate([
            'meter_no' => ['required',
                Rule::unique('cns_consumer_meters', 'meter_no')->where(function($q) {
                    $q->where('status', MeterStatus::ACTIVE->value);
                }),
            ],
            'meter_serial_no' => [
                Rule::requiredIf($consumer->connection_type_id == 2), 
                'nullable',
                Rule::unique('cns_consumer_meters', 'meter_serial_no')->where(function($q) {
                    $q->where('status', MeterStatus::ACTIVE->value);
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
                'doc_type_id' => DocumentType::METER_IMAGE->value,
                'file_id' => $documents_bulk['file_list'][1]['file_id'],
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
            'status' => 1,
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
            'status_id' => EnumsConsumerStatus::EXECUTE->value,
            'notes' => $request->notes,
            'created_by' => Auth::id(),
        ]);
        // Sms Notification
        $sms_response = SmsService::dispatch($consumer, new ExecuteSmsNotification(['crn' => $consumer->crn]));
        // Response
        return response()->json(['success' => 'Consumer executed successfully!']);
    }
} 