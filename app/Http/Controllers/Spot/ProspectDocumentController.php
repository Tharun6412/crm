<?php
namespace App\Http\Controllers\Spot;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Master\DocumentCentre\DocumentUpload;
use App\Models\DocumentCentre\DocumentTypes;
use App\Models\Spot\ProspectDocuments;
use App\Models\Spot\Prospects;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
require_once app_path('Helpers/spotauth.php');


class ProspectDocumentController extends Controller
{
    /**
     * Display the Document Status form
     * Loads the DocuementTypes with type = 4 and prospect details
     * type = 2 [document-status]
     * @return view
     */
    public function create(Request $request, $id)
    {
        $document_types = DocumentTypes::where('type', 4)->get();
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

    /**
     * Adds the Document.
     * 
     * This Method:
     * - Validates the document_type.
     * -count the document types (No. of documents based on type)
     * Loads the DocumentUpload Class to upload documents.
     * 
     * @return response string
     */
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
     * 
     * This Method:
     * - Fetch the ProspectDocuments details
     * - Load the DocumentUpload class to implement the delete functionality
     * 
     * @return response string
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