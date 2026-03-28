<?php

namespace App\Http\Controllers\Consumer;

use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use Illuminate\Http\Request;

class ConsumerSearchController extends Controller
{
    /**
     * Quick Search 
     */
    public function search(Request $request)
    {
        // Get input search key

        // Get consumers
        $consumers = Consumer::with('ga:id,name', 'status:id,name')->select('id','connection_type_id', 'crn', 'fname', 'lname', 'ga_id', 'status_id')
            ->when($request->has('q'), function($q) use($request) {
                $q->whereAny(['crn', 'fname', 'lname', 'email', 'phone'], 'like', '%' . $request->q . '%');
            })
            ->limit(10)->get();

        // Response
        return response()->json($consumers);
    }
}