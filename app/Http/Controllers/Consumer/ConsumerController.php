<?php

namespace App\Http\Controllers\Consumer;

use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use Illuminate\Http\Request;

class ConsumerController extends Controller
{
    /**
     * Index
     */
    public function index(Request $request)
    {
        // Get consumers
        $consumers = Consumer::
            when($request->has('key'), function ($q) use($request) {
                $q->where(function ($q) use($request) {
                    $q->where('crn', 'like', '%' . $request->key . '%')
                        ->orWhere('fname', 'like', '%' . $request->key . '%')
                        ->orWhere('lname', 'like', '%' . $request->key . '%');
                });
            })
            ->paginate(50)->withQueryString();
        
        // Render output
        return view('consumers.consumers.list', ['consumers' => $consumers]);
    }
}