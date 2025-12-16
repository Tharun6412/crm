<?php

namespace App\Http\Controllers\Api\V1\Application;

use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ConsumerController extends Controller
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
        // Get consumers list
        $consumers_q = Consumer::with(['ga:id,code,name', 'status:id,name'])->select('id', 'crn', 'fname', 'lname', 'ga_id', 'status_id')
            ->when((!$request->user()->isAdmin() AND !$request->user()->isSuperAdmin()), function ($q) use($request) {
                $q->whereIn('ga_id', $request->user()->ga()->pluck('ga_id')->toArray());
            })
            ->when($request->has('key'), function ($q) use($request) {
                $q->whereAny(['crn', 'fname', 'lname', 'email', 'phone'], 'like', '%' . $request->key . '%');
            })
            ->paginate(2);
            $consumers = $this->apiPagination($consumers_q);
        
        return response()->json(['consumers' => $consumers, 'user' => $request->user()->isAdmin()], 200);
    }

    /**
     * Consumer details
     */
    public function details(Request $request, $id)
    {
        // Find Consumer
        $consumer = Consumer::when((!$request->user()->isAdmin() AND !$request->user()->isSuperAdmin()), function ($q) use($request) {
                $q->whereIn('ga_id', $request->user()->ga()->pluck('ga_id')->toArray());
            })->find($id);

        // Abort if consumer not found
        if (! $consumer) {
            return response()->json(['error' => 'Consumer not found'], 403);
        }
        // Get consumer details
        return response()->json(['consumer' => $consumer], 200);
    }
}