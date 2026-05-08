<?php

namespace App\Services;

use App\Models\Admin\UserExport;
use Illuminate\Support\Facades\Auth;

class UserExportService
{
    /**
     * Create export record
     * @param string $fileName
     */
    public static function create($fileName)
    {
        // Check the queue count
        $in_queue_count = UserExport::where(['user_id' => Auth::id(), 'status' => 0])->get()->count();
        // Check user export limit
        if($in_queue_count < 3) {
            // Add export record
            $new_export_id = UserExport::create([
                'user_id' => Auth::id(),
                'file_name' => $fileName,
                'status' => 0, // 0: pending, 1: completed
            ]);
            return $new_export_id;
        }
        else {
            return false;
        }
    }
}