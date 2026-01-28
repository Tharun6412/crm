<?php

namespace App\Http\Controllers\Master\DocumentCentre;

use App\Http\Controllers\Controller;
use App\Models\DocumentCentre\Documents;
use App\Models\DocumentCentre\DocumentTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * Document Browser
 */
class DocumentBrowser extends Controller
{
    /**
     * Index method
     */
    public function index()
    {
        // Get document / file types
        $dc_types = DocumentTypes::all();
        return view('dc.browser.browse', ['dc_types' => $dc_types, 'add_session' => true]);
    }

    /**
     * Search documents
     */
    public function search(Request $request)
    {
        // Validation
        $val_rslt = $request->validate(['search_key' => 'required']);

        $dc_files = Documents::where('doc_number', 'like', '%' . $request->search_key . '%')
            ->orWhere('file_name', 'like', '%' . $request->search_key . '%')
            ->limit(10)->get();
        return view('dc.browser.file_browser_result', ['dc_files' => $dc_files]);
    }

    /**
     * Select files from search results
     */
    public function selectFiles(Request $request)
    {
        $request->validate(['dc_files' => 'required']);
        // dd($request->dc_files);
        // Select details from database and update session
        $dc_files_db = Documents::whereIn('id', $request->dc_files)->get();
        $dc_file_session = ($request->session()->has('dc_files')) ? $request->session()->get('dc_files') : [];
        foreach($dc_files_db as $dc_file) {
            $dc_file_session[$dc_file->id] = [
                'id' => $dc_file->id,
                'doc_number' => $dc_file->doc_number,
                'file_name' => $dc_file->file_name,
                'file_path' => $dc_file->file_path,
                'upload' => 0,
            ];
        }
        // Update session
        $request->session()->put('dc_files', $dc_file_session);
        // Response in JSON
        return response()->json(['success' => 'Documents added successfully!']);
    }

    /**
     * Uploaded and session stored files
     */
    public function sessionFiles(Request $request)
    {
        return view('dc.browser.session_files', ['dc_files' => $request->session()->get('dc_files')]);
    }

    /**
     * Delete file from uploaded session
     */
    public function deleteFile(Request $request)
    {
        // Delete from session
        $dc_files = $request->session()->get('dc_files');
        if(isset($dc_files[$request->id])) {
            $dc_file = $dc_files[$request->id];
            /**
             * Check and delete the uploaded file
             * This was disabled, Documents can be managed in Document Centre module only.
             */
            // if($dc_file['upload'] == 1) {
                // Delete file from storage
                // Storage::disk('public')->delete($dc_file['file_path']);
                // Delete from database
                // Documents::destroy($request->id);
            // }
            // Delete from array and update session
            unset($dc_files[$request->id]);
            $request->session()->put('dc_files', $dc_files);
        }
    }
}