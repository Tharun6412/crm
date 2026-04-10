<?php
namespace App\Http\Controllers\Consumer;

use App\Contracts\Prepaid\Acquisition;
use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Enums\MeterStatus;
use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerMeter;
use App\Models\Payments\PayRecharge;
use App\Models\Master\MasterConsumerStatus;
use App\Models\Master\PriceGroups;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Prepaid Consumers Controller
 * 
 */
class PrepaidConsumerController extends Controller
{
    /**
     * Index
     * 
     * ConsumerStatus will map the URL slug with database and returns object
     */
    public function index(Request $request, MasterConsumerStatus $status)
    {
        // Get consumers
        $consumers = Consumer::with([
                'segment:id,name',
                'status',
                'ga',
                'district',
                'scheme.scheme',
                'meter',
                'activeMeter',
                'prepaidData',
                'priceGroup:id,code',
            ])
            ->select(['id', 'crn', 'fname', 'lname', 'segment_id', 'connection_type_id', 'status_id', 'ga_id', 'district_id', 'price_group_id', 'created_at'])
            ->when((!isAdmin() AND !isSuperAdmin()), function ($q) {
                $q->whereIn('ga_id', session('user')['gas']);
            })
            ->when($request->filled('key'), function ($q) use($request) {
                $q->whereAny(['crn', 'fname', 'lname', 'email', 'phone'], 'like', '%' . $request->key . '%')
                ->orWhereHas('activeMeter', fn ($q) => $q->where('meter_no', 'like', '%' . $request->key . '%'));
            })
            ->when($request->has('segments'), function ($q) use($request) {
                $q->whereIn('segment_id', $request->segments);
            })
            ->when($request->has('geo_area'), function ($q) use($request) {
                $q->whereIn('ga_id', $request->geo_area);
            })
            ->when($request->has('cns_status'), function ($q) use($request) {
                $q->whereIn('status_id', $request->cns_status);
            })
            ->when(($status->id != null), function($q) use($status) {
                $q->where('status_id', $status->id);
            })
            ->when($request->has('hes_status'), function($q) use($request) {
                $q->whereHas('prepaidData', fn ($q) => $q->whereIn('hes_status', $request->hes_status));
            })
            ->where('connection_type_id', 2)
            ->paginate(50)->withQueryString();
        
        // Render output
        if($request->ajax()) {
            return view('consumers.prepaid.list-body', ['consumers' => $consumers]);
        }
        else {
            return view('consumers.prepaid.list', ['consumers' => $consumers]);
        }
    }
    /**
     * Send consumer data to HES Server.
     * 
     */
    public function sendToHes($id)
    {
        $consumer = Consumer::where('connection_type_id', 2)->find($id);
        if(!$consumer) {
            abort(422, "Trying to send invalid consumer, Please check.");
        }
        // Get Price groups
        $price_groups = PriceGroups::where(['ga_id' => $consumer->ga_id, 'segment_id' => $consumer->segment_id])->get();
        return view('consumers.prepaid.send-hes', ['consumer' => $consumer, 'price_groups' => $price_groups]);
    }

    /**
     * Submit the data to HES server.
     * 
     */
    public function hesSubmit(Request $request, $id)
    {
        // Validation
        $request->validate([
            'meter_sr_no' => 'required|min:12',
            'meter_no' => 'required',
            'price_group_id' => 'required',
            'vcf' => 'required|Numeric|gte:1|lt:2',
        ]);
        // 1.Fetch consumer
        $consumer = Consumer::where('connection_type_id', 2)->where('id', $id)->firstOrFail();
        // 2. Ensure prepaid data exists
        if (!$consumer->prepaidData) {
            return response()->json(['message' => 'Prepaid data not found for this consumer'], 422);
        }
        // Update price_group_id in consumers
        $consumer->update([
            'price_group_id' => $request->price_group_id,
        ]);
        // 3. update or create the consumer meter details.
        ConsumerMeter::updateOrCreate(
            [
                'consumer_id' => $consumer->id,
                'status'   => MeterStatus::ACTIVE->value,   // match only the active record
            ],
            [
                'meter_no'    => $request->meter_no,
                'meter_serial_no' => $request->meter_sr_no,
                'vcf'         => $request->vcf,
                'status'   => MeterStatus::ACTIVE->value,
            ]
        );
        // 3. Load the contract and call the API.
        $acquisition = new Acquisition;
        $response = $acquisition->push($consumer);

        // dd($response);

        // 4. Success case
        if ($response->successful()) {
            // // 5. update the HES status as sent.
            // $consumer->prepaidData->update([
            //     'hes_status' => 1,
            //     'hes_date'   => now()->toDateString(),
            // ]);
            // // 6. return the success response
            // return response()->json(['success' => 'Consumer details sent to HES successfully!']);
            $responseData = $response->json();
            // Check the actual status from response body
            $hesStatus = $responseData['Integ_Response'][0]['status'] ?? null;

            if ($hesStatus === 'success') {
                $consumer->prepaidData->update([
                    'hes_status' => 1,
                    'hes_date'   => now()->toDateString(),
                ]);
                return response()->json(['success' => 'Consumer details sent to HES successfully!']);
            } else {
                return response()->json([
                    'error'   => 'Consumer details not send to HES.',
                    'message' => $responseData['Integ_Response'][0]['message'] ?? 'Unknown error',
                    'error_code' => $responseData['Integ_Response'][0]['error_code'] ?? null,
                ], 422);
            }
        }
        else {
            // 7. Failure response.
            return response()->json([
                'error'   => 'Failed to send consumer details to HES',
                'status'  => $response->status(),
                'message' => $response->json('message')
                    ?? 'HES API error. Please check logs.',
            ], 500);
        }
    }

    /**
     * View Details
     */
    public function consumerRechargeList(Request $request, $id)
    {
        $recharges = PayRecharge::where('consumer_id', $id)->orderBy('created_at', 'desc')->paginate(20)->withQueryString();
        return view('consumers.consumers.show-recharge', ['recharges' => $recharges]);
    }
}