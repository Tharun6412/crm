<?php

namespace App\Http\Controllers\Consumer;

use App\Enums\AwsPath;
use App\Enums\ConsumerStatus;
use App\Enums\DocumentType;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Master\DocumentCentre\DocumentUpload;
use App\Models\Admin\User;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerDocument;
use App\Models\DocumentCentre\Documents;
use App\Models\DocumentCentre\DocumentTypes;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ConsumerDocumentController extends Controller
{
    /**
     * Consumer Document
     * @param $consumer_id
     */
    public function edit(Request $request, $id) 
    {
        $document_types = DocumentTypes::all();
        return view('consumers.documents.edit', [
            'id' => $id,
            'document_types' => $document_types,
        ]);
    }

    /**
     * Consumer Document Update
     * @param $consumer_id
     */
    public function update(Request $request, $id)
    {
        // Validation
        $request->validate([
            'dc_type' => 'required',
            'file' => 'required|file|max:51200',
        ]);

        // Upload process
        if($request->file('file')) {
            $file_name = $request->file->getClientOriginalName();
            $upload_path = 'consumers/' . date('ym');
            // Upload only in production
            
            if(config('app.env') == 'production') {
                // Upload file to AWS S3 bucket
                $file_path = Storage::disk('s3')->put($upload_path, $request->file);
                // Get the file url
                // $file_url = Storage::disk('s3')->url($file_path);
            }
            else {
                $file_path = $file_name;
            }
            // Create a DB record in Document Centre package
            $dc_insert = Documents::create([
                'disk' => 's3',
                'file_type_id' => $request->dc_type,
                'file_name' => $file_name,
                'file_path' => $file_path,
                'status' => 1,
                'created_by' => Auth::id(),
            ]);
            $doc_number = 'DC' . str_pad($dc_insert->id, 9, '0', STR_PAD_LEFT);
            $dc_update = Documents::where('id', $dc_insert->id)->update(['doc_number' => $doc_number]);
            // Consumer Document Upload
            $consumer = Consumer::find($id);
            ConsumerDocument::create([
                'consumer_id' => $id,
                'status_id' => NULL,
                'doc_type_id' => $request->dc_type,
                'file_id' => $dc_insert->id,
            ]);
            // Return response
            return response()->json(['success' => 'Document uploaded and document number generated successfully with document number: ' . $doc_number]);
        }
        else {
            // Failed
            return response()->json(['error' => 'Upload failed.']);
        }
    }
} 