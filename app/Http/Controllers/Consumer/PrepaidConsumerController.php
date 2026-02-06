<?php
namespace App\Http\Controllers\Consumer;

use App\Contracts\Prepaid\Acquisition;
use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use App\Models\Master\MasterConsumerStatus;
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
        $consumers = Consumer::when((!isAdmin() AND !isSuperAdmin()), function ($q) {
                $q->whereIn('ga_id', session('user')['gas']);
            })
            ->when($request->has('key'), function ($q) use($request) {
                $q->whereAny(['crn', 'fname', 'lname', 'email', 'phone'], 'like', '%' . $request->key . '%')
                ->orWhereHas('activeMeter', function ($mq) use($request) {
                    $mq->where('meter_no', 'like', $request->key);
                });
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
        return view('consumers.prepaid.send-hes', ['consumer' => $consumer]);
    }

    /**
     * Submit the data to HES server.
     * 
     */
    public function hesSubmit(Request $request, $id)
    {
        // 1.Fetch consumer
        $consumer = Consumer::where('connection_type_id', 2)
        ->where('id', $id)
        ->firstOrFail();
        // 2. Ensure prepaid data exists
        if (!$consumer->prepaidData) {
            return response()->json(['error' => 'Prepaid data not found for this consumer'], 422);
        }
        // 3. Load the contract and call the API.
        $acquisition = new Acquisition;
        $response = $acquisition->push($consumer);
        // 4. Success case
        if ($response->successful()) {
            // 5. update the HES status as sent.
            $consumer->prepaidData->update([
                'hes_status' => 1,
                'hes_date'   => now()->toDateString(),
            ]);
            // 6. return the success response
            return response()->json(['success' => 'Consumer details sent to HES successfully!']);
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
     * Mro Request API
     */
    public function mroRequest(Request $request)
    {
        $consumer = Consumer::where('connection_type_id',2)->get();
    }
}