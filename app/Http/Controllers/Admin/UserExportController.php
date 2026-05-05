<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AppModule;
use App\Models\Admin\Module;
use App\Models\Admin\Role;
use App\Models\Admin\UserExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * Roles controller
 */
class UserExportController extends Controller
{
    /**
     * Index method
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        // Get all roles
        $exports = UserExport::where('user_id', $userId)->orderBy('created_at', 'desc')->limit(20)->get();
        return view('admin.exports.list', ['exports' => $exports]);
    }

    /**
     * To Delete the Export File
     */
    public function destroy(Request $request, $id)
    {
        // To Delete the Export record
        $export = UserExport::find($id);
        if($export) {
            // Try delete
            try {
                // Delete from database
                UserExport::destroy($id);
            } catch (\Throwable $th) {
                // Throw this message if the file used 
                return response()->json(['status' => 1, 'msg' => 'This record cannot be deleted']);
            }
            // Check file exists or not
            if(Storage::disk('public')->exists($export->file_name)) {
                // Delete file from storage
                Storage::disk('public')->delete($export->file_name);
                return response()->json(['status' => 1, 'msg' => 'Record deleted successfully!']);
            }
            else {
                return response()->json(['status' => 2, 'msg' => 'Record deleted, file not found!']);
            }
        }
        else {
            return response()->json(['status' => 0, 'msg' => 'Invalid Export File!']);
        }
    }
}