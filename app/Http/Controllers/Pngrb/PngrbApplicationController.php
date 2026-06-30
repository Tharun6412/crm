<?php

namespace App\Http\Controllers\Pngrb;

use App\Contracts\PngrbUnifiedPortal\ConsumerApplication;
use App\Http\Controllers\Controller;
use App\Models\Consumer\PngrbApplications;
use App\Models\DocumentCentre\DocumentTypes;
use App\Models\Master\ConnectionType;
use App\Models\Master\ConsumerGasRequired;
use App\Models\Master\ConsumerNomineeRelation;
use App\Models\Master\Ga;
use App\Models\Master\Segment;
use App\Models\Master\Title;
use Illuminate\Http\Request;

class PngrbApplicationController extends Controller
{
    /**
     * List Applications
     */
    public function index(Request $request)
    {
        $sortBy = ($request->filled('sortBy')) ? $request->get('sortBy') : 'created_at';
        $sortOr = ($request->filled('sortOr')) ? $request->get('sortOr') : 'desc';
        $applications = PngrbApplications::when($request->has('search_key'), function($q) use($request) {
            $q->whereAny(['name', 'applicationNumber', 'email'], $request->search_key);
        })->orderBy($sortBy, $sortOr)->paginate(20)->withQueryString();
        // Response
        if($request->ajax()) {
            return view('pngrb.applications.list-body', ['applications' => $applications]);
        }
        return view('pngrb.applications.list', ['applications' => $applications]);
    }

    /**
     * PNGRB Display
     * @param int $id Application Id
     */
    public function show(Request $request, $id)
    {
        $application = PngrbApplications::find($id);
        return view('pngrb.applications.show', ['application' => $application]);
    }

    /**
     * Consumer Registration from PNGRB application.
     * @param int $id
     */
    public function edit(Request $request, $id)
    {
        $application = PngrbApplications::find($id);
        $geo_areas = Ga::all();
        return view('pngrb.applications.edit', [
            'geo_areas' => $geo_areas,
            'districts' => [],
            'charge_areas' => [],
            'areas' => [],
            'subareas' => [],
            'segments' => Segment::all(),
            'titles' => Title::all(),
            'nominee_relations' => ConsumerNomineeRelation::all(),
            'documents' => DocumentTypes::where('type', 1)->get(),
            'gas_required_list' => ConsumerGasRequired::all(),
            'schemes' => [],
            'connection_types' => ConnectionType::all(),
            'application' => $application,
        ]);
    }

    /**
     * Store the consumer to TR (in Pulse)
     * @param int $id
     */
    public function update(Request $request, $id)
    {
        dd($request->all());
    }

    /**
     * Approve Or Reject the PNGRB Application
     * @param int $id
     */
    public function approveApplication(Request $request, $id)
    {
        $application = PngrbApplications::find($id);
        $status_ar[] = [
            'applicationNumber' => $application->applicationNumber,
            'status' => "APPROVED",
            'remarks' => "string",
            'cgdId' => "CGD-192",
        ];
        $response = ConsumerApplication::updateStatus($status_ar);
        if($response) {
            // Success reposne
        }
        else {
            // failure response.
        }
    }
}