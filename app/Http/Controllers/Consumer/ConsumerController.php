<?php

namespace App\Http\Controllers\Consumer;

use App\Exports\Consumers\ConsumerExport;
use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerDocument;
use App\Models\Master\MasterConsumerStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ConsumerController extends Controller
{
    /**
     * Index
     * 
     * ConsumerStatus will map the URL slug with database and returns object
     */
    public function index(Request $request, MasterConsumerStatus $status)
    {
        // Get consumers
        $sortBy = ($request->get('sortBy')) ? $request->get('sortBy') : 'created_at';
        $sortOr = ($request->get('sortOr')) ? $request->get('sortOr') : 'desc';
        $records = ($request->get('records')) ? $request->get('records') : 50;
        // Query
        $consumers = Consumer::with(['segment', 'status', 'ga', 'district', 'scheme'])
            ->when((!isAdmin() AND !isSuperAdmin() AND isFullAccess()), function ($q) {
                $q->whereIn('ga_id', session('user')['gas']);
            })
            ->when($request->filled('key'), function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    $query->whereAny(['crn', 'fname', 'lname', 'email', 'phone'], 'like', '%' . $request->key . '%')
                    ->orWhereHas('meter', function ($q1) use ($request) {
                        $q1->whereAny(['meter_no', 'meter_serial_no'], 'like', '%' . $request->key . '%');
                    });
                });
            })
            ->when($request->has('segments'), function ($q) use($request) {
                $q->whereIn('segment_id', $request->segments);
            })
            ->when($request->filled('connection_type_id'), function ($q) use($request) {
                $q->where('connection_type_id', $request->connection_type_id);
            })
            ->when($request->has('geo_area'), function ($q) use($request) {
                $q->whereIn('ga_id', $request->geo_area);
            })
            ->when($request->has('district'), function ($q) use($request) {
                $q->whereIn('district_id', $request->district);
            })
            ->when($request->has('charge_area'), function ($q) use($request) {
                $q->whereIn('ca_id', $request->charge_area);
            })
            ->when($request->has('area'), function ($q) use($request) {
                $q->whereIn('area_id', $request->area);
            })
            ->when($request->has('scheme'), function ($q) use($request) {
                $q->where(function($query) use($request) {
                    $query->whereHas('scheme', function($q1) use($request) {
                        $q1->whereIn('scheme_id', $request->scheme);
                    });
                });
            })
            ->when($request->has('cns_status'), function ($q) use($request) {
                $q->whereIn('status_id', $request->cns_status);
            })
            ->when((!empty($request->date_from) and !empty($request->date_to)), function($q) use($request) {
                $q->whereBetween('created_at', [Carbon::createFromFormat('d-m-Y', $request->date_from)->startOfDay()->toDateTimeString(), Carbon::createFromFormat('d-m-Y', $request->date_to)->endOfDay()->toDateTimeString()]);
            })
            ->when(($status->id != null), function($q) use($status) {
                $q->where('status_id', $status->id);
            })
            ->orderBy($sortBy, $sortOr)->paginate($records)->withQueryString();
        
        // Render output
        if($request->ajax()) {
            // dd($request->all());
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
        return view('consumers.consumers.show', [
            'consumer' => $consumer,
            'consumer_meter' => $consumer->meter->where('status', 1)->first(),
        ]);
    }

    /**
     * Consumer Documents
     */
    public function consumerDocs(Request $request, $id)
    {
        $documents_list = ConsumerDocument::where('consumer_id', $id)->get();
        return view('consumers.consumers.show-documents', ['documents_list' => $documents_list]);
    }
    /**
     * Consumers Export
     */
    public function consumerExport(Request $request)
    {
        return (new ConsumerExport($request))->download('consumers.xlsx');
    }
}