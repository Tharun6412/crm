<?php

namespace App\Http\Controllers\Pngrb;

use App\Http\Controllers\Controller;
use App\Models\Consumer\PngrbApplications;
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

    public function edit(Request $request, $id)
    {
        $application = PngrbApplications::find($id);
        return view('pngrb.applications.edit', ['application' => $application]);
    }
}