<?php

namespace App\Http\Controllers\Master\DocumentCentre;

use App\Http\Controllers\Controller;
use App\Models\DocumentCentre\Documents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentUpload extends Controller
{
    /**
     * Upload file from multiple modules
     */
    static function upload($request, $package = 'crm')
    {
        // Validation
        $request->validate([
            'dc_file' => 'required|file|max:51200',
            'tag' => 'max:30',
            'description' => 'max:90',
        ]);

        // Upload process
        if($request->file('dc_file')) {
            $file_name = $request->dc_file->getClientOriginalName();
            $upload_path = $package . '/' . date('ym');

            // Upload to AWS S3 bucket only in production
            $file_path = Storage::disk('s3')->put($upload_path, $request->dc_file);
            // Upload to AWS S3 bucket only in production
            if(config('app.env') == 'development') {
                $file_path = $file_name;
            } else {
                $file_path = Storage::disk('s3')->put($upload_path, $request->dc_file);
            }
            
            // Create a DB record in Document Centre package
            $dc_insert = Documents::create([
                'disk' => 's3',
                'file_name' => $file_name,
                'file_path' => $file_path,
                'status' => 1,
                'tags' => $request->tag,
                'description' => $request->description,
                'created_by' => Auth::id(),
            ]);
            $doc_number = 'DC' . str_pad($dc_insert->id, 9, '0', STR_PAD_LEFT);
            $dc_update = Documents::where('id', $dc_insert->id)->update(['doc_number' => $doc_number]);
            return ['file_id' => $dc_insert->id, 'doc_number' => $doc_number];
        }
        else {
            return ['file_id' => null];
        }
    }

    /**
     * Multiple Files Upload
     */
    static function uploadBulk($request, $package = 'crm')
    {
        // Validations
        $request->validate([
            // 'dc_file_list' => 'required|array',
            'dc_file_list.0' => 'required|file|max:51200',
            'dc_file_list.1' => 'required|file|max:51200',
            'tag' => 'max:30',
            'description' => 'max:90',
        ]);
        $file_data = [];
        if($request->file('dc_file_list')) {
            foreach($request->dc_file_list as $key => $file) {
                $file_name = $file->getClientOriginalName();
                $upload_path = $package . '/' . date('ym');
                
                // Upload to AWS S3 bucket only in production
                if(config('app.env') == 'development') {
                    $file_path = $file_name;
                } else {
                    $file_path = Storage::disk('s3')->put($upload_path, $file);
                }
                
                // Check file
                if(!empty($file)) {
                    // Create a DB record in Document Centre package
                    $dc_insert = Documents::create([
                        'disk' => 's3',
                        'file_name' => $file_name,
                        'file_path' => $file_path,
                        'status' => 1,
                        'tags' => $request->tag,
                        'description' => $request->description,
                        'created_by' => Auth::id(),
                    ]);
                    $doc_number = 'DC' . str_pad($dc_insert->id, 9, '0', STR_PAD_LEFT);
                    $dc_update = Documents::where('id', $dc_insert->id)->update(['doc_number' => $doc_number]);
                    $file_data[] = [
                        'file_id' => $dc_insert->id,
                        'doc_number' => $doc_number,
                    ]; 
                }
            }
            return ['file_list' => $file_data];
        }
        else {
            return ['file_list' => null];
        }
    }

    /**
     * Optional Bulk Documents
     */
    static function uploadIfPresent($request, $package = 'crm')
    {
        // Validations
        $request->validate([
            'dc_file_list' => 'array',
            'tag' => 'max:30',
            'description' => 'max:90',
        ]);
        $file_data = [];
        if($request->file('dc_file_list')) {
            foreach($request->dc_file_list as $key => $file) {
                $file_name = $file->getClientOriginalName();
                $upload_path = $package . '/' . date('ym');
                
                // Upload to AWS S3 bucket only in production
                if(config('app.env') == 'development') {
                    $file_path = $file_name;
                } else {
                    $file_path = Storage::disk('s3')->put($upload_path, $file);
                }
                if(!empty($file)) {
                    // Create a DB record in Document Centre package
                    $dc_insert = Documents::create([
                        'disk' => 's3',
                        'file_name' => $file_name,
                        'file_path' => $file_path,
                        'status' => 1,
                        'tags' => $request->tag,
                        'description' => $request->description,
                        'created_by' => Auth::id(),
                    ]);
                    $doc_number = 'DC' . str_pad($dc_insert->id, 9, '0', STR_PAD_LEFT);
                    $dc_update = Documents::where('id', $dc_insert->id)->update(['doc_number' => $doc_number]);
                    $file_data[] = [
                        'file_id' => $dc_insert->id,
                        'doc_number' => $doc_number,
                    ]; 
                }
            }
            return ['file_list' => $file_data];
        }
        else {
            return ['file_list' => null];
        }
    }

    /**
     * Delete file from external packages
     * @var int dc_file_id
     * @return array
     */
    static function delete($id)
    {
        // Get document details
        $file = Documents::find($id);
        if($file) {
            // Try delete
            try {
                // Delete from database
                Documents::destroy($id);
                // Check file exists or not
                if(Storage::disk('s3')->exists($file->file_path)) {
                    // Delete file from storage AWS S3
                    Storage::disk('s3')->delete($file->file_path);
                    return ['status' => 1, 'msg' => 'Document deleted successfully!'];
                }
                else {
                    return ['status' => 2, 'msg' => 'Document deleted, file not found!'];
                }
            } catch (\Throwable $th) {
                // Throw this message if the file used 
                return ['status' => 2, 'msg' => 'Document cannot be deleted!'];
            }    
        }
        else {
            return ['status' => 2, 'msg' => 'Invalid document!'];
        }
    }
}