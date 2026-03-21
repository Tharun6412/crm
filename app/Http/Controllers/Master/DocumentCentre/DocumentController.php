<?php

namespace App\Http\Controllers\Master\DocumentCentre;

use App\Http\Controllers\Controller;
use App\Models\DocumentCentre\Documents;
use App\Models\DocumentCentre\DocumentTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Index 
     */
    public function index(Request $request)
    {
        // Get Documents
        $documents = Documents::when($request->has('search_key'), function($q) use ($request) {
            $q->where(function($q) use($request) {
                $q->where('doc_number', 'like', '%' . $request->search_key . '%');
                $q->orWhere('file_name', 'like', '%' . $request->search_key . '%');
                $q->orWhereHas('createdBy', function($q) use ($request) {
                    $q->where('first_name', 'like', '%' . $request->search_key . '%');
                });
            });
        })
        ->when($request->has('doc_types'), function($q) use($request) {
            return $q->whereIn('dc_type_id', $request->doc_types);
        })
        ->orderBy('created_at', 'DESC')->paginate(10);
        // Append to pagination
        $documents->appends(['search_key' => $request->search_key]);

        if($request->ajax())
            return view('dc.documents.list-body', ['documents' => $documents]);
        else
            return view('dc.documents.list', ['documents' => $documents]);
    }

    /**
     * Create
     */
    public function create()
    {
        // Get types
        $dc_types = DocumentTypes::all();
        return view('dc.documents.create', ['dc_types' => $dc_types]);
    }

    /**
     * Show
     */
    public function show($id)
    {
        $file = Documents::find($id);
        if($file) {
            if(Storage::disk('s3')->exists($file->file_path)) {
                $ext = strtolower(substr($file->file_name, -4));
                if($ext == '.jpg' OR $ext == '.png' OR $ext == 'jpeg' OR $ext == '.gif') {
                    // Get file contents & view in browser
                    $file_data = Storage::disk('s3')->get($file->file_path, $file->file_name);
                    return response()->make($file_data, 200, [
                        'Content-Type' => 'image/jpeg',
                        'Content-Disposition' => 'inline; filename="' . $file->file_name . '"'
                    ]);
                }
                else if($ext == '.pdf') {
                    // Get file contents & view in browser
                    $file_data = Storage::disk('s3')->get($file->file_path, $file->file_name);
                    return response()->make($file_data, 200, [
                        'Content-Type' => 'application/pdf',
                        'Content-Disposition' => 'inline; filename="' . $file->file_name . '"'
                    ]);
                }
                else {
                    return Storage::download($file->file_path, $file->file_name);
                }
            }
            else {
                return view('utils.file-not-found');
            }
        }
        else {
            return view('utils.file-not-found');
        }
    }

    /**
     * Download file
     */
    public function Download($id)
    {
        $file = Documents::find($id);
        if($file) {
            if(Storage::disk('s3')->exists($file->file_path)) {
                // return Storage::disk('s3')->download($file->file_path, $file->file_name_original);
                return Storage::download($file->file_path, $file->file_name);
            }
            else {
                return view('utils.file-not-found');
            }
        }
        else {
            return view('utils.file-not-found');
        }
    }

    /**
     * File uploader
     * Uplod the file from dc.browser.file_uploader
     */
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'file' => 'required|file|max:51200',
            'tag' => 'max:30',
            'description' => 'max:90',
        ]);

        // Upload process
        if($request->file('file')) {
            $file_name = $request->file->getClientOriginalName();
            $upload_path = 'general/' . date('ym');
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
                'tags' => $request->tag,
                'description' => $request->description,
                'created_by' => Auth::id(),
            ]);
            $doc_number = 'DC' . str_pad($dc_insert->id, 9, '0', STR_PAD_LEFT);
            $dc_update = Documents::where('id', $dc_insert->id)->update(['doc_number' => $doc_number]);

            // Add session when the request from proposal modules
            if($request->has('add_session')) {
                // Create a session with file and path
                $file_session = ($request->session()->has('dc_files')) ? $request->session()->get('dc_files') : [];
                $file_session[$dc_insert->id] = [
                    'id' => $dc_insert->id,
                    'doc_number' => $doc_number,
                    'file_name' => $file_name,
                    'file_path' => $file_path,
                    'upload' => 1,
                ];
                $request->session()->put('dc_files', $file_session);
            }

            // Return response
            return response()->json(['success' => 'Document uploaded and document number generated successfully with document number: ' . $doc_number]);
        }
        else {
            // Failed
            return response()->json(['error' => 'Upload failed.']);
        }
    }

    /**
     * Delete file
     */
    public function destroy($id)
    {
        // Get document details
        $file = Documents::find($id);
        if($file) {
            // Try delete
            try {
                // Delete from database
                Documents::destroy($id);
            } catch (\Throwable $th) {
                // Throw this message if the file used 
                return response()->json(['status' => 1, 'msg' => 'Document cannot be deleted!']);
            }
            // Check file exists or not
            if(Storage::disk('s3')->exists($file->file_path)) {
                // Delete file from stirage AWS S3
                Storage::disk('s3')->delete($file->file_path);
                return response()->json(['status' => 1, 'msg' => 'Document deleted success fully!']);
            }
            else {
                return response()->json(['status' => 2, 'msg' => 'Document deleted, file not found!']);
            }
        }
        else {
            return response()->json(['status' => 0, 'msg' => 'Invalid document!']);
        }
    }
}