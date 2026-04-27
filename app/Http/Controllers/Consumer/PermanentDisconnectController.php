<?php
namespace App\Http\Controllers\Consumer;

use App\Enums\ConsumerStatus as EnumsConsumerStatus;
use App\Enums\MeterStatus;
use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerMeter;
use App\Models\Consumer\ConsumerStatus;
use App\Notifications\Consumer\PdSmsNotification;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermanentDisconnectController extends Controller
{
    /**
     * Index method
     */
    public function index(Request $request)
    {
        echo "test method";
    }
    /**
     * Permanent disconnection form
     */
    public function edit(Request $request, $id) 
    {
        return view('consumers.pd.edit', ['id' => $id]);
    }

    /**
     * Permanent disconnect
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'notes' => 'required|max:255',
        ]);
        // 8 = PD
        $consumer = Consumer::find($id);
        $consumer->update([
            'status_id' => EnumsConsumerStatus::PD->value,
            'updated_by' => Auth::id(),
        ]);
        // Consumer Status History
        ConsumerStatus::create([
            'consumer_id' => $id,
            'status_id' => EnumsConsumerStatus::PD->value,
            'notes' => $request->notes,
            'created_by' => Auth::id(),
        ]);
        // Consumer Meter Status update
        $consumer_meter = ConsumerMeter::where('consumer_id', $id)->where('status', MeterStatus::ACTIVE->value)->first();
        if($consumer_meter) {
            $consumer_meter->update([
                'status' => MeterStatus::INACTIVE->value,
            ]);
        }
        // Sms Notification
        $sms_response = SmsService::dispatch($consumer, new PdSmsNotification(['crn' => $consumer->crn]));
        // Response
        return response()->json(['success' => 'Consumer Permanently Disconnected.Go to <a href="'.url('consumers/pd').'">Consumers List</a>']);
    }
} 