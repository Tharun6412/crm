<?php

namespace App\Http\Controllers\Consumer;

use App\Http\Controllers\Controller;
use App\Models\Admin\User;
use App\Models\Consumer\Consumer;
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
        $meterChange = ConsumerMeterChanges::all();
        return view('consumers.meter-change.list', [
            'meterChange' => $meterChange,
        ]);
    }
    /**
     * Consumer Scheme Accept State
     */
    public function edit(Request $request, $id) 
    {
        $consumer_meter= ConsumerMeter::where('consumer_id', $id)->where('status', 1)->first();
        $meter_data = BillInvoiceConsumption::where('meter_id', $consumer_meter->id)->latest('id')->first();
        $users = User::select('id', 'first_name', 'last_name', 'emp_id')->whereIn('department_id', [3, 5])->get();
        return view('consumers.meter-change.create', [
            'consumer_meter' => $consumer_meter, 
            'id' => $id,
            'meter_data' => $meter_data,
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
            'prev_reading' => 'required|numeric',
            'end_reading' => 'required|numeric|gt:prev_reading',
            'request_date' => 'required',
            'release_date' => 'required',
            'technician_id' => 'required',
            'reason' => 'required|max:255',
        ]);
        // Fetch Old Meter Details
        $old_meter = ConsumerMeter::where(['consumer_id' => $id, 'status' => 1])->latest('id')->first();
        // Old Consumer Meter Update status = Replaced[3]
        $old_meter->update([
            'status' => 3,
            'updated_by' => Auth::id(),
        ]);
        // Add New Meter Record with Active Status
        $new_meter = ConsumerMeter::create([
            'consumer_id' => $id,
            'meter_no' => $request->meter_no,
            'meter_serial_no' => $request->meter_serial_no,
            'initial_reading' => $request->prev_reading,
            'install_date' => Carbon::now(),
            'install_by' => Auth::id(),
            'status' => 1,
            'created_by' => Auth::id(),
        ]);
        // Meter Reading Calculations
        $consumption = round($request->end_reading - $request->prev_reading, 3);
        // Add Meter Change record
        ConsumerMeterChanges::create([
            'consumer_id' => $id,
            'meter_id' => $old_meter->id,
            'prev_reading' => $request->prev_reading,
            'end_reading' => $request->end_reading,
            'consumption' => $consumption,
            'new_meter_id' => $new_meter->id,
            'request_date' => Carbon::createFromFormat('d-m-Y', $request->request_date),
            'replace_date' => Carbon::createFromFormat('d-m-Y', $request->release_date),
            'reason' => $request->reason,
            'status_id' => 1,
            'technician_id' => $request->technician_id,
            'created_by' => Auth::id(),
        ]);
        // Response
        return response()->json(['success' => 'Consumer meter details updated Successfully!']);
    }
} 