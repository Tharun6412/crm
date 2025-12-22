<?php
namespace App\Http\Controllers\Consumer;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DocumentCentre\DocumentUpload;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerDocument;
use App\Models\Consumer\ConsumerStatus;
use App\Models\Consumer\ConsumerMeter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $request->validate([
            'meter_no' => 'required|unique:cns_consumer_meters,meter_no',
            'meter_reading' => 'required|numeric',
            'notes' => 'required',
        ]);
        // Meter Images Upload
         // Documents Data Preparation
        $documents_bulk = DocumentUpload::uploadBulk($request, 'domestic');
        if($request->has('dc_file_list')) {
            foreach($request->dc_file_list as $key => $doc_type) {
                $add_consumer_document = ConsumerDocument::create([
                    'consumer_id' => $id,
                    'status_id' => 4,
                    'doc_type_id' => 5,
                    'file_id' => $documents_bulk['file_list'][$key]['file_id'],
                ]);
            }
        }
        // Consumer Meter
        ConsumerMeter::create([
            'consumer_id' => $id,
            'meter_no' => $request->meter_no,
            'initial_reading' => $request->meter_reading,
            'install_date' => Carbon::now(),
            'install_by' => Auth::id(),
            'status' => 1,
            'created_by' => Auth::id(),
        ]);
        // 4 = Execution
        Consumer::where('id', $id)->update([
            'status_id' => 4,
            'updated_by' => Auth::id(),
        ]);
        // Status History
        ConsumerStatus::create([
            'consumer_id' => $id,
            'status_id' => 4,
            'notes' => $request->notes,
            'created_by' => Auth::id(),
        ]);
        // Response
        return response()->json(['success' => 'Consumer executed successfully!']);
    }
} 