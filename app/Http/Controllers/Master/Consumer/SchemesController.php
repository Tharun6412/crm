<?php

namespace App\Http\Controllers\Master\Consumer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\ConsumerScheme;
use App\Models\Master\ConsumerSchemeGa;
use App\Models\Master\Ga;
use App\Models\Master\Segment;
use Illuminate\Support\Facades\Auth;


class SchemesController extends Controller
{
    // Index Function
    public function index(Request $request) {
        $sortBy = ($request->get('sortBy')) ? $request->get('sortBy') : 'created_at';
        $sortOr = ($request->get('sortOr')) ? $request->get('sortOr') : 'desc';
        $records = ($request->get('records')) ? $request->get('records') : 10;
        $query = ConsumerScheme::with(['segment', 'schemePayment', 'schemesGa.ga'])->when($request->has('search_key'), function($q) use($request) {
            $q->where(function($q) use($request) {
                $q->where('name', 'like', '%'.$request->get('search_key').'%');
            });
        });
        $schemes = $query->orderBy($sortBy, $sortOr)->paginate($records)->withQueryString();
        if($request->ajax()) {
            return view('master.consumer.schemes.list-body', ['schemes' => $schemes]);
        }
        else {
            return view('master.consumer.schemes.list', ['schemes' => $schemes]);
        }
    }

    public function show($id)
    {
        $scheme = ConsumerScheme::find($id);
        return view('master.consumer.schemes.show', ['scheme' => $scheme]);
    }

    public function create()
    {
        $gas = Ga::all();
        $segments = Segment::all();
        return view('master.consumer.schemes.create', ['gas' => $gas, 'segments' => $segments]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'segment' => 'required',
            'name' => 'required',
            'registration' => 'required|numeric',
            'security' => 'required|numeric',
            'consumption' => 'required|numeric',
            'min_payment' => 'required|numeric',
            'applicable_ga' => 'required|array|min:1',
        ]);

        // TO insert into the Schemes
        $add_scheme = ConsumerScheme::create([
            'segment_id' => $request->segment,
            'code' => $request->code,
            'name' => $request->name,
            'registration' => $request->registration,
            'security' => $request->security,
            'consumption' => $request->consumption,
            'total_deposit' => ($request->security + $request->consumption),
            'min_payment' => $request->min_payment,
            'emi_amount' =>  !empty($request->emi_amount) ? $request->emi_amount : 0.00,
            'rental_amount' =>  !empty($request->rental_amount) ? $request->rental_amount : 0.00,
            'created_by' => Auth::id(),
        ]);
        if($add_scheme)
        {
            $ga_ar = [];
            foreach ($request->applicable_ga as $key => $ga) {
                $ga_ar[] = [
                    'scheme_id' =>$add_scheme->id,
                    'ga_id' => $ga,
                ];
            }
            ConsumerSchemeGa::upsert($ga_ar,['scheme_id', 'ga_id'],['scheme_id', 'ga_id']);
            // Response Message
            return response()->json(['success' => 'Scheme Details Created Successfully']);
        }
        return response()->json(['success' => 'Error in Inserting Data']);
    
    }

    public function edit($id)
    {
        $scheme = ConsumerScheme::find($id);
        $gas = Ga::all();
        $segments = Segment::all();
        return view('master.consumer.schemes.edit', ['gas' => $gas, 'segments' => $segments, 'scheme' => $scheme]);

    }

    public function update(Request $request, $id)
    {
         $request->validate([
            'segment' => 'required',
            'name' => 'required',
            'registration' => 'required|numeric',
            'security' => 'required|numeric',
            'consumption' => 'required|numeric',
            'min_payment' => 'required|numeric',
            'applicable_ga' => 'required|array|min:1',
        ]);

        $scheme = ConsumerScheme::find($id);
        // TO Update into the Schemes
        $update_scheme = $scheme->update([
            'segment_id' => $request->segment,
            'code' => $request->code,
            'name' => $request->name,
            'registration' => $request->registration,
            'security' => $request->security,
            'consumption' => $request->consumption,
            'total_deposit' => ($request->security + $request->consumption),
            'min_payment' => $request->min_payment,
            'emi_amount' =>  !empty($request->emi_amount) ? $request->emi_amount : 0.00,
            'rental_amount' =>  !empty($request->rental_amount) ? $request->rental_amount : 0.00
        ]);
        if($update_scheme)
        {
            $scheme->gas()->sync($request->applicable_ga);
            // $scheme->schemesGa()->sync($request->applicable_ga);
            // ConsumerSchemeGa::upsert($ga_ar,['scheme_id', 'ga_id'],['scheme_id', 'ga_id']);
            // Response Message
            return response()->json(['success' => 'Scheme Details Created Successfully']);
        }
        return response()->json(['success' => 'Error in Inserting Data']);
    }

    public function destroy($id)
    {

    }

    public function toggleStatus($id)
    {
        $scheme = ConsumerScheme::findOrFail($id);

        // Toggle status (1 → 0, 0 → 1)
        $scheme->status = !$scheme->status;
        $scheme->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.',
            'status' => $scheme->status ? 'Enabled' : 'Disabled'
        ]);
    }

}   
