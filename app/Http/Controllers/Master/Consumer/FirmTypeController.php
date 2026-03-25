<?php

namespace App\Http\Controllers\Master\Consumer;

use App\Http\Controllers\Controller;
use App\Models\Master\FirmType;
use Illuminate\Http\Request;

class FirmTypeController extends Controller
{
    /**
     * Index
     * List of Firm Types
     */
    public function index(Request $request)
    {
        $firm_types = FirmType::when($request->has('key'), function($q) use($request) {
                $q->whereAny(['name'], 'like', '%' . $request->key . '%');
            })->orderByDesc('id')->get();
        // Render view
        if($request->ajax())
            return view('master.consumer.firm-types.list-body', ['firm_types' => $firm_types]);
        else
            return view('master.consumer.firm-types.list', ['firm_types' => $firm_types]);
    }
}