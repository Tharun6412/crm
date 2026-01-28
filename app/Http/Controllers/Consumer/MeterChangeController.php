<?php

namespace App\Http\Controllers\Consumer;

use App\Enums\ConsumerStatus as EnumsConsumerStatus;
use App\Enums\DocumentType;
use App\Enums\MeterStatus;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Master\DocumentCentre\DocumentUpload;
use App\Models\Admin\User;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerDocument;
use App\Models\Consumer\ConsumerMeter;
use App\Models\Consumer\ConsumerMeterChanges;
use App\Models\Consumer\ConsumerStatus;
use App\Models\Invoice\BillInvoiceConsumption;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class MeterChangeController extends Controller
{
    /**
     * Index method
     */
    public function index(Request $request)
    {
        $meterChange = ConsumerMeterChanges::when($request->has('key'), function ($q) use($request) {
                $q->whereHas('meter', function($q1) use($request) {
                    $q1->whereAny(['meter_no'], 'like', '%' . $request->key . '%');
                });
            })->orderBy('created_at', 'desc')->paginate(20)->withQueryString();
        if($request->ajax()) {
            return view('consumers.meter-change.list-body', [
                'meterChange' => $meterChange,
            ]);        
        }
        return view('consumers.meter-change.list', [
            'meterChange' => $meterChange,
        ]);
    }

    /**
     * Fetch By Meter
     * @param $meter_id
     */
    public function show(Request $request, $id)
    {
        $consumer_meter = ConsumerMeterChanges::find($id);
        return view('consumers.meter-change.show',['consumer_meter' => $consumer_meter]);
    }
    /**
     * Consumer Scheme Accept State
     */
    public function edit(Request $request, $id) 
    {
        $consumer_meter= ConsumerMeter::where('consumer_id', $id)->where('status', 1)->first();
        $users = User::select('id', 'first_name', 'last_name', 'emp_id')->whereIn('department_id', [3, 5])->get();
        return view('consumers.meter-change.create', [
            'consumer_meter' => $consumer_meter, 
            'id' => $id,
            'users' => $users,
        ]);
    }

    /**
     * Consumer Meter Change
     * @param $consumer_id
     */
    public function update(Request $request, $id)
    {
        // Validation Message
        $old_meter = ConsumerMeter::where(['consumer_id' => $id, 'status' => 1])->first();
        $prev_reading = round(($old_meter->meterConsumption?->curr_reading ?? $old_meter->initial_reading), 3);
        $request->validate([
            'meter_no' => ['required',
                Rule::unique('cns_consumer_meters', 'meter_no')->where(function($q) {
                    $q->where('status', 1);
                }),
            ],
            'meter_serial_no' => ['nullable', 
                Rule::unique('cns_consumer_meters', 'meter_serial_no')->where(function($q) {
                    $q->where('status', 1);
                }),
            ],
            'prev_reading' => 'required|numeric|in:'.$prev_reading,
            'end_reading' => 'required|numeric|gt:'.$request->prev_reading,
            'initial_reading' => 'required|numeric',
            'request_date' => 'required',
            'release_date' => 'required',
            'technician_id' => 'required',
            'reason' => 'required|max:255',
        ], [
            'prev_reading.in' => "Previous Reading must be exactly ".$prev_reading,
        ]);
        $doc_upload = DocumentUpload::upload($request, 'domestic');
        // Fetch Old Meter Details
        //Meter Image Upload
        ConsumerDocument::create([
            'consumer_id' => $id,
            'status_id' => EnumsConsumerStatus::HSC->value,
            'doc_type_id' => DocumentType::METER_IMAGE->value,
            'file_id' => $doc_upload['file_id'],
        ]);
        // Old Consumer Meter Update status = Replaced[3]
        $old_meter->update([
            'status' => MeterStatus::Replace->value,
            'updated_by' => Auth::id(),
        ]);
        // Add New Meter Record with Active Status
        $new_meter = ConsumerMeter::create([
            'consumer_id' => $id,
            'file_id' => $doc_upload['file_id'],
            'meter_no' => $request->meter_no,
            'meter_serial_no' => $request->meter_serial_no,
            'initial_reading' => $request->initial_reading,
            'install_date' => Carbon::now(),
            'install_by' => Auth::id(),
            'status' => MeterStatus::Active->value,
            'created_by' => Auth::id(),
        ]);
        // Meter Reading Calculations
        $consumption = round($request->end_reading - $request->prev_reading, 3);
        // Add Meter Change record
        ConsumerMeterChanges::create([
            'consumer_id' => $id,
            'meter_id' => $old_meter->id,
            'file_id' => $doc_upload['file_id'],
            'prev_reading' => $request->prev_reading,
            'end_reading' => $request->end_reading,
            'consumption' => $consumption,
            'new_meter_id' => $new_meter->id,
            'request_date' => Carbon::createFromFormat('d-m-Y', $request->request_date),
            'replace_date' => Carbon::createFromFormat('d-m-Y', $request->release_date),
            'reason' => $request->reason,
            'status_id' => 1, //1 = Pending, 2 = Closed
            'technician_id' => $request->technician_id,
            'created_by' => Auth::id(),
        ]);
        // Response
        return response()->json(['success' => 'Consumer meter details updated Successfully!']);
    }
} 