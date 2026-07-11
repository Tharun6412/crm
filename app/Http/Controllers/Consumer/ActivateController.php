<?php
namespace App\Http\Controllers\Consumer;

use App\Enums\AwsPath;
use App\Enums\Constants;
use App\Enums\ConsumerStatus as EnumsConsumerStatus;
use App\Enums\DocumentType;
use App\Enums\MeterStatus;
use App\Enums\ReferralStatus;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Master\DocumentCentre\DocumentUpload;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerData;
use App\Models\Consumer\ConsumerDocument;
use App\Models\Consumer\ConsumerMeter;
use App\Models\Consumer\ConsumerStatus;
use App\Models\Master\LpgOmc;
use App\Notifications\Consumer\ActivateSmsNotification;
use App\Services\ConsumerStatusService;
use App\Services\ReferralService;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ActivateController extends Controller
{
    /**
     * Index method
     */
    public function index(Request $request)
    {
        echo "test method";
    }

    /**
     * Activate form
     * @param int $id Consumer ID
     */
    public function edit(Request $request, $id) 
    {
        $omcs = LpgOmc::all();
        $consumer = Consumer::with(['consumerData', 'activeMeter'])->findOrFail($id);
        return view('consumers.activate.edit', [ 'consumer' => $consumer,'omcs' => $omcs,]);
    }

    /**
     * Activate consumer
     * #Activated state
     * @param int $id Consumer ID
     */
    public function update(Request $request, $id)
    {
        $consumer = Consumer::findOrFail($id);
        $meter = $consumer->activeMeter;
        // Validation
        $request->validate([
            'notes' => 'required|max:255',
            // 'lpg_consumer_number' => 'trim',
            // 'lpg_id' => 'trim',
            // 'lpg_omc_id' => 'trim',
            // 'registered_mobile' => 'trim|digits:10',
            // 'lpg_connections' => 'trim|max:17',
            'meter_no' => ['required',
                Rule::unique('cns_consumer_meters', 'meter_no')->ignore($meter?->id)->where(function($q) {
                    $q->where('status', MeterStatus::ACTIVE->value);
                }),
            ],
            'meter_serial_no' => [
                Rule::requiredIf($consumer->connection_type_id == 2), 
                'nullable',
                Rule::unique('cns_consumer_meters', 'meter_serial_no')->ignore($meter?->id)->where(function($q) {
                    $q->where('status', MeterStatus::ACTIVE->value);
                }),
            ],
            'meter_reading' => 'required|numeric',
        ]);
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
        ConsumerData::where('consumer_id', $id)->update([
            'lpg_consumer_number' => $request->lpg_consumer_number,
            'lpg_id' => $request->lpg_id,
            'lpg_omc_id' => $request->lpg_omc_id,
            'registered_mobile' => $request->registered_mobile,
            'lpg_connections' => $request->lpg_connections,
        ]);
        // Consumer Meter
        ConsumerMeter::where('consumer_id', $id)->where('status',1)->update([
            'meter_no' => $request->meter_no,
            'meter_serial_no' => $request->meter_serial_no,
            'initial_reading' => $request->meter_reading,
            'updated_by' => Auth::id(),
        ]);
        
        // Consumer Target Status 
        $target_status = ConsumerStatusService::update($id, EnumsConsumerStatus::ACTIVATE->value);
        // Status History
        ConsumerStatus::create([
            'consumer_id' => $id,
            'status_id' => EnumsConsumerStatus::ACTIVATE->value,
            'notes' => $request->notes,
            'created_by' => Auth::id(),
        ]);
        // check the referal request and redeem the amount.
        $redeem = ReferralService::redeem($id);

        // SmS Integration
        $sms_response = SmsService::dispatch($consumer, new ActivateSmsNotification(['crn' => $consumer->crn]));
        // Response
        return response()->json(['success' => 'Consumer activated successfully!']);
    }
} 