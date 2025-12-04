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
     * Insert consumer
     */
    public function store()
    {
        // Validate

        // Insert data

        // Send SMS

        // Rsponse
    }

    /**
     * Consumer details
     */
    public function details()
    {
        // Get consumer details
    }
}