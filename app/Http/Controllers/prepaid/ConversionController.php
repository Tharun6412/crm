<?php

namespace App\Http\Controllers\prepaid;

use App\Enums\ConnectionType;
use App\Enums\InvoiceStatus;
use App\Enums\MeterStatus;
use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerMeter;
use App\Models\Consumer\ConsumerScheme;
use App\Models\Consumer\ConsumerSchemeHistory;
use App\Models\Consumer\Prepaid;
use App\Models\Invoice\BillInvoice;
use App\Models\Master\ConsumerSchemeGa;
use App\Models\Master\MasterConsumerScheme;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ConversionController extends Controller
{
    /**
     * Edit
     * 
     * Show conversion form
     * @param int $consumer_id consumer id
     */
    public function edit($consumer_id)
    {
        // Get the consumer details
        $consumer = Consumer::find($consumer_id);
        // Get outstanding balances
        $os_balance = BillInvoice::selectRaw('SUM(balance_amount) as balance')->where('consumer_id', $consumer_id)->whereNot('status_id', InvoiceStatus::CANCEL->value)->first();
        // Get all prepaid schemes in the GA
        $ga_schemes = MasterConsumerScheme::whereHas('gas', function ($q) use($consumer) {
                $q->where('ga_id', $consumer->ga_id);
            })
            ->where('connection_type_id', ConnectionType::PREPAID->value)
            ->where('total_deposit', '>=', ($consumer->scheme->paid_deposit ?? 0))
            ->get();

        // Render output
        return view('consumers.conversion.conversion', [
            'consumer' => $consumer,
            'os_balance' => $os_balance,
            'ga_schemes' => $ga_schemes,
        ]);
    }

    /**
     * Update
     */
    public function update(Request $request, $id)
    {
        // Validation
        $request->validate([
            'bill_date' => 'required',
            'bill_qty' => 'required|numeric',
            'bill_amount' => 'required|numeric',
            'bill_status' => 'required',
            'new_scheme' => 'required',
            'meter_no' => ['required',
                Rule::unique('cns_consumer_meters', 'meter_no')->where(function($q) {
                    $q->where('status', MeterStatus::ACTIVE->value);
                }),
            ],
            'meter_sno' => ['required',
                Rule::unique('cns_consumer_meters', 'meter_serial_no')->where(function($q) {
                    $q->where('status', MeterStatus::ACTIVE->value);
                }),
            ],
            'meter_reading' => 'required',
        ]);

        // Get consumer details
        $consumer = Consumer::find($id);
        // Get New scheme details
        $new_scheme = MasterConsumerScheme::find($request->new_scheme);

        // Insert into cns_prepaid table
        $prepaid_consumer = Prepaid::create([
            'consumer_id' => $id,
            'post_scheme_id' => $consumer->scheme->scheme_id,
            'pre_scheme_id' => $request->new_scheme,
            'conversion_date' => date('Y-m-d'),
            'bill_date' => Carbon::createFromFormat('d-m-Y', $request->bill_date),
            'bill_qty' => $request->bill_qty,
            'bill_amount' => $request->bill_amount,
            'bill_status' => $request->bill_status,
            'created_by' => Auth::id(),
        ]);

        // Calculate SD paid and new scheme differences
        $new_balance = $new_scheme->total_deposit - $consumer->scheme->paid_deposit;
        // update consumer scheme
        $update_consumer_scheme = ConsumerScheme::where('consumer_id', $id)->update([
            'scheme_id' => $new_scheme->id,
            'security_deposit' => $new_scheme->security,
            'consumption_deposit' => $new_scheme->consumption,
            'total_deposit' => $new_scheme->total_deposit,
            'emi_amount' => $new_scheme->emi_amount,
            'rental_amount' => $new_scheme->rental_amount,
            'paid_deposit' => $consumer->scheme->paid_deposit,
            'balance' => $new_balance,
            'status' => ($new_balance > 0) ? 0 : 1,
        ]);

        // Create history record
        $scheme_history = ConsumerSchemeHistory::create([
            'consumer_id' => $consumer->scheme->consumer_id,
            'scheme_id' => $consumer->scheme->scheme_id,
            'security_deposit' => $consumer->scheme->security_deposit,
            'consumption_deposit' => $consumer->scheme->consumption_deposit,
            'total_deposit' => $consumer->scheme->total_deposit,
            'emi_amount' => $consumer->scheme->emi_amount,
            'rental_amount' => $consumer->scheme->rental_amount,
            'paid_deposit' => $consumer->scheme->paid_deposit,
            'balance' => $consumer->scheme->balance,
            'status' => $consumer->scheme->status,
        ]);

        // Update consumer connection type to prepaid
        $consumer->connection_type_id = ConnectionType::PREPAID->value;
        $consumer->save();
        // Inactive the previous active meters.
        $meter_update = ConsumerMeter::where('consumer_id', $consumer->id)->where('status', MeterStatus::ACTIVE->value)->update(['status' => MeterStatus::INACTIVE->value]);
        // Add new meter
        $new_meter = ConsumerMeter::create([
            'consumer_id' => $id,
            'meter_no' => $request->meter_no,
            'meter_serial_no' => $request->meter_sno,
            'initial_reading' => $request->meter_reading,
            'status' => MeterStatus::ACTIVE->value,
            'created_by' => Auth::id(),
        ]);

        // Response
        return response()->json(['success' => 'Scheme updated successfully!']);
    }
}