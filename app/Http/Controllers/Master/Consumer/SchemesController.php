<?php

namespace App\Http\Controllers\Master\Consumer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\ConsumerScheme;


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
        return view('master.consumer.schemes.show', ['scheme', $scheme]);
    }

    public function create()
    {

    }

    public function strore()
    {

    }

    public function edit($id)
    {

    }

    public function update()
    {

    }

    public function destroy($id)
    {

    }
}
