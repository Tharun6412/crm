<?php

namespace App\Http\Controllers\Billing;

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
            // Query List based on GA
            $consumers = Consumer::when((!isAdmin() AND !isSuperAdmin() AND !isFullAccess()), function ($q) {
                $q->whereIn('ga_id', session('user')['gas']);
            })
            ->select('id', 'crn', 'fname', 'lname', 'ga_id', 'status_id', 'created_by')
            ->when($request->filled('search'), function($q) use($request) {
                $q->whereAny(['crn', 'fname', 'lname'], 'like', '%'.$request->search.'%');
            })
            ->where('status_id', ConsumerStatus::ACTIVATE->value)
            ->latest()->limit(20)->get();
            // Ajax Response
            return view('billing.billing.list-body', ['consumers' => $consumers]);
        }
        // Response
        return view('billing.billing.list');
    }
}