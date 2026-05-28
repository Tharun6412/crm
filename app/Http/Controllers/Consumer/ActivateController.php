<?php
namespace App\Http\Controllers\Consumer;

use App\Enums\AwsPath;
use App\Enums\ConsumerStatus as EnumsConsumerStatus;
use App\Enums\DocumentType;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Master\DocumentCentre\DocumentUpload;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerDocument;
use App\Models\Consumer\ConsumerStatus;
use App\Notifications\Consumer\ActivateSmsNotification;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
     */
    public function edit(Request $request, $id) 
    {
        return view('consumers.activate.edit', ['id' => $id]);
    }

    /**
     * Activate consumer
     * #Activated state
     */
    public function update(Request $request, $id)
    {
        // Validation
        $request->validate([
            'notes' => 'required|max:255',
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
        $consumer = Consumer::find($id);
        $consumer->update([
            'status_id' => EnumsConsumerStatus::ACTIVATE->value,
            'activation_date' => now()->toDateTimeString(),
            'updated_by' => Auth::id(),
        ]);
        // Status History
        ConsumerStatus::create([
            'consumer_id' => $id,
            'status_id' => EnumsConsumerStatus::ACTIVATE->value,
            'notes' => $request->notes,
            'created_by' => Auth::id(),
        ]);

        // SmS Integration
        $sms_response = SmsService::dispatch($consumer, new ActivateSmsNotification(['crn' => $consumer->crn]));
        // Response
        return response()->json(['success' => 'Consumer activated successfully!']);
    }
} 