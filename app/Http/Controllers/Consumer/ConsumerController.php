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
        // print_r(session('user'));
        // Get consumers
        $consumers = Consumer::when((!isAdmin() AND !isSuperAdmin()), function ($q) {
                $q->whereIn('ga_id', session('user')['gas']);
            })
            ->when($request->has('key'), function ($q) use($request) {
                $q->whereAny(['crn', 'fname', 'lname', 'email', 'phone'], 'like', '%' . $request->key . '%');
            })
            ->when($request->has('segments'), function ($q) use($request) {
                $q->whereIn('segment_id', $request->segments);
            })
            ->when($request->has('geo_area'), function ($q) use($request) {
                $q->whereIn('ga_id', $request->geo_area);
            })
            ->when($request->has('cns_status'), function ($q) use($request) {
                $q->whereIn('status_id', $request->cns_status);
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
        $consumer = Consumer::when((!isAdmin() AND !isSuperAdmin()), function ($q) {
                $q->whereIn('ga_id', session('user')['gas']);
            })->find($id);

        // Abort if consumer not found
        if (! $consumer) {
            abort(404, 'Consumer not found');
        }

        // Render output
        return view('consumers.consumers.show', ['consumer' => $consumer]);
    }
}