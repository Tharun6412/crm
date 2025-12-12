<?php

namespace App\Http\Controllers\Master\Consumer;

use App\Http\Controllers\Controller;
use App\Models\Master\Price;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    /**
     * Index
     */
    public function index(Request $request)
    {
        // Get prices and show
        $gas_prices = Price::when($request->has('key'), function ($q) use($request) {
            $q->whereAny(['basic', 'basic_price'], 'like', '%' . $request->key . '%');
        })
        ->when($request->has('segments'), function ($q) use($request) {
            $q->whereIn('segment_id', $request->segments);
        })
        ->when($request->has('geo_area'), function ($q) use($request) {
            $q->whereHas('district', function ($q) use($request) {
                $q->whereIn('ga_id', $request->geo_area);
            });
        })
        ->paginate(50);

        // Render view
        if($request->ajax())
            return view('master.consumer.prices.list-body', ['gas_prices' => $gas_prices]);
        else
            return view('master.consumer.prices.list', ['gas_prices' => $gas_prices]);
    }
    
    /**
     * Create Price
     */
    public function create()
    {
        // Create form
        return view('master.consumer.prices.create');
    }

    /**
     * Store price data
     */
    public function store(Request $request)
    {
        // Validations

        // Store data

        // Responses
        return response()->json(['success' => 'Price record created successfully!']);
    }
}