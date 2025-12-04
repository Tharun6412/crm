<?php
namespace App\Http\Controllers\Consumer;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DocumentCentre\DocumentUpload;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerDocument;
use App\Models\Consumer\ConsumersStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HscController extends Controller
{
    /**
     * Index method
     */
    public function index(Request $request)
    {
        echo "test method";
    }
    /**
     * Consumer HSC State
     */
    public function edit(Request $request, $id) 
    {
        return view('consumers.hsc.edit', [
            'id' => $id, 
        ]);
    }

    /**
     * Accept/Reject
     * Registered -> Accept/Reject
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'notes' => 'required|max:255',
        ]);
        $doc_upload = DocumentUpload::upload($request, 'domestic');
        //HSC Image Upload
        ConsumerDocument::create([
            'consumer_id' => $id,
            'status_id' => 5,
            'doc_type_id' => 6,
            'file_id' => $doc_upload['file_id'],
        ]);
        // 5 = HSC
        Consumer::where('id', $id)->update([
            'status_id' => 5,
            'updated_by' => Auth::id(),
        ]);
        // Consumer Status History
        ConsumersStatus::create([
            'consumer_id' => $id,
            'status_id' => 5,
            'notes' => $request->notes,
            'created_by' => Auth::id(),
        ]);
        // Response
        return response()->json(['success' => 'Consumer HSC successfully completed!']);
    }
} 