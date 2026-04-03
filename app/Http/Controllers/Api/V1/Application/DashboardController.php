<?php

namespace App\Http\Controllers\Api\V1\Application;

use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use App\Models\Invoice\BillInvoice;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Adjust pagination
     */
    use ApiResponse;

    /**
     * List
     */
    public function list(Request $request)
    {
        // Get Consumers Data
        $consumer_status = Consumer::select('status_id', DB::raw('COUNT(id) as status_count'))
            ->when((!$request->user()->isApiAdmin() AND !$request->user()->isApiSuperAdmin() AND !$request->user()->isApiFullAccess()), function ($q) use($request) {
                $q->whereIn('ga_id', $request->user()->ga()->pluck('ga_id')->toArray());
            })->groupBy('status_id')->get();
        // Response
        return response()->json(['consumer_status' => $consumer_status, 'user' => $request->user()->isAdmin()], 200);
    }
}