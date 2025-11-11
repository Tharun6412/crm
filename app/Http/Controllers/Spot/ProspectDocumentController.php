<?php
namespace App\Http\Controllers\Spot;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DocumentCentre\DocumentUpload;
use App\Models\Spot\DocumentTypes;
use App\Models\Spot\ProspectDocuments;
use App\Models\Spot\Prospects;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
require_once app_path('Helpers/spotauth.php');


class ProspectDocumentController extends Controller
{
    // Index Function
    public function index()
    {

    }

    // Add Document Based on Prospect ID
    public function create(Request $request, $id)
    {
        $document_types = DocumentTypes::all();
        $prospect = Prospects::find($id);
        if($request->type == "2") {
            return view('spot.prospects.documents.create', [
                'document_types' => $document_types,
                'type' => 2,
                'id' => $id,
            ]);    
        }
        return view('spot.prospects.show', [
            'document_types' => $document_types,
            'type' => 2,
            'id' => $id,
            'prospect' => $prospect
        ]);
    }

    // To Insert Document
    public function store(Request $request, $id)
    {
        // Validation
        $request->validate([
            'document_type_id' => 'required',
        ]);
        $document_upload = DocumentUpload::upload($request, 'spot');
        $doc_offer_count = ProspectDocuments::where('prospect_id', $id)->where('document_type_id', $request->document_type_id)->count();
        // To insert into the Prospect Documents
        ProspectDocuments::create([
            'prospect_id' => $id,
            'document_type_id' => $request->document_type_id,
            'offer_count' => $doc_offer_count+1,
            'doc_file_id' => $document_upload['file_id'],
            'created_at' => Carbon::now(),
            'created_by' => Auth::id(), 
        ]);
        // Session::flash('success', 'Document Details Added successfully');
        return response()->json(['success' => 'Document Details Added Successfully']);
    }
    /**
     * To delete the Document
     */
    public function destroy($id)
    {
        $document = ProspectDocuments::find($id);
        if($document)
        {
            // Unlinking the Document
            DocumentUpload::delete($document->doc_file_id);
            $document->delete();
            Session::flash('doc_success', 'Record Deleted Successfully');
            // return response()->json(['success' => 'Record Deleted Successfully']);
        }
    }
}