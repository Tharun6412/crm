<?php
namespace App\Http\Controllers\Consumer;

use App\Contracts\Prepaid\Acquisition;
use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use Illuminate\Http\Request;

/**
 * Prepaid Consumers Controller
 * 
 */
class PrepaidConsumerController extends Controller
{

    public function sendToHes($id)
    {
        $consumer = Consumer::where('connection_type_id', 2)->find($id);
        if(!$consumer) {
            abort(422, "Trying to send invalid consumer, Please check.");
        }
        return view('consumers.prepaid.sendToHes', ['consumer' => $consumer]);
    }

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
}