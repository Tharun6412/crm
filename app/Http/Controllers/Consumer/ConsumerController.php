<?php

namespace App\Http\Controllers\Consumer;

use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use App\Models\Master\ConsumerStatus;
use Illuminate\Http\Request;

class ConsumerController extends Controller
{
    /**
     * Index
     * 
     * ConsumerStatus will map the URL slug with database and returns object
     */
    public function index(Request $request, ConsumerStatus $status)
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
            ->when(($status->id != null), function($q) use($status) {
                $q->where('status_id', $status->id);
            })
            ->paginate(50)->withQueryString();
        
        // Render output
        if($request->ajax()) {
            return view('consumers.consumers.list-body', ['consumers' => $consumers]);
        }
        else {
            return view('consumers.consumers.list', ['consumers' => $consumers]);
        }
    }

    /**
     * Show
     * 
     * Consumer details
     */
    public function show($id)
    {
        // Find Consumer
        $consumer = Consumer::find($id);

        // Abort if consumer not found
        if (! $consumer) {
            abort(404, 'Consumer not found');
        }

        // Render output
        return view('consumers.consumers.show', ['consumer' => $consumer]);
    }
}