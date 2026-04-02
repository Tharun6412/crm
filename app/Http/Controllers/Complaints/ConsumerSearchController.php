<?php

namespace App\Http\Controllers\Complaints;

use App\Enums\ConsumerStatus;
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
        // Get consumers
        if($request->ajax()) {
            if(empty($request->search)) {
                return response()->json(['message' => 'Please enter consumer number'], 422);
            }
            $consumers = Consumer::select('id', 'crn', 'fname', 'lname', 'ga_id', 'status_id', 'created_by')
                ->when(!(isAdmin() OR isSuperAdmin() OR isFullAccess()), function ($q) {
                    $q->whereIn('ga_id', session('user')['gas']);
                })
                ->when($request->has('search'), function($q) use($request) {
                    $q->where('crn', 'like', '%'.$request->search.'%');
                })
                ->paginate(20)->withQueryString();
            // Ajax Response
            return view('complaints.calls.list-body', ['consumers' => $consumers]);
        }
        // Response
        return view('complaints.calls.list');
    }
}