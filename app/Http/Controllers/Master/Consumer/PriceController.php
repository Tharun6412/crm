<?php

namespace App\Http\Controllers\Master\Consumer;

use App\Http\Controllers\Controller;
use App\Models\Master\Ga;
use App\Models\Master\Price;
use App\Models\Master\PriceHistory;
use App\Models\Master\Segment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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
        // Get data
        $segments = Segment::all();
        $geo_areas = Ga::all();
        // Render output
        return view('master.consumer.prices.create', [
            'segments' => $segments,
            'geo_areas' => $geo_areas,
        ]);
    }

    /**
     * Store price data
     */
    public function store(Request $request)
    {
        // Validations
        $request->validate([
            'segment_id' => 'required',
            'ga_id' => 'required',
            'district_id' => ['required', Rule::unique(Price::class)->where('segment_id', $request->segment_id)],
            'basic' => 'required|numeric',
            'supply' => 'required|numeric',
            'margin' => 'required|numeric',
            'vat' => 'required|numeric',
            'effective_from' => 'required',
        ]);

        // Calculations
        $basic_price = $request->basic + $request->supply + $request->margin;
        $tax_price = round(($basic_price * $request->vat ) / 100, 2);

        // Store data in price
        $new_price = Price::create([
            'segment_id' => $request->segment_id,
            'district_id' => $request->district_id,
            'basic' => $request->basic,
            'supply' => $request->supply,
            'margin' => $request->margin,
            'basic_price' => $basic_price,
            'tax_value' => $request->vat,
            'tax_price' => $tax_price,
            'rsp' => ($basic_price + $tax_price),
            'effective_from' => Carbon::createFromFormat('d-m-Y', $request->effective_from),
            'created_by' => Auth::id(),
        ]);
        
        // Store data price history
        $new_price_history = PriceHistory::create([
            'price_id' => $new_price->id,
            'segment_id' => $request->segment_id,
            'district_id' => $request->district_id,
            'basic' => $request->basic,
            'supply' => $request->supply,
            'margin' => $request->margin,
            'basic_price' => $basic_price,
            'tax_value' => $request->vat,
            'tax_price' => $tax_price,
            'rsp' => ($basic_price + $tax_price),
            'effective_from' => Carbon::createFromFormat('d-m-Y', $request->effective_from),
            'created_by' => Auth::id(),
        ]);

        // Responses
        return response()->json(['success' => 'Price record created successfully!']);
    }

    /**
     * Show details
     */
    public function show($id)
    {
        $price_details = Price::find($id);

        return view('master.consumer.prices.show', ['price_details' => $price_details]);
    }

    /**
     * Edit
     */
    public function edit($id)
    {
        // Get price details
        $price_details = Price::findorFail($id);

        return view('master.consumer.prices.edit', ['price_details' => $price_details]);
    }

    /**
     * Update price details
     */
    public function update(Request $request, $id)
    {
        // Validations
        $request->validate([
            'basic' => 'required|numeric',
            'supply' => 'required|numeric',
            'margin' => 'required|numeric',
            'vat' => 'required|numeric',
            'effective_from' => 'required',
        ]);

        // Calculations
        $basic_price = $request->basic + $request->supply + $request->margin;
        $tax_price = round(($basic_price * $request->vat ) / 100, 2);

        // Update data in price
        $price_update = Price::where('id', $id)->update([
            'basic' => $request->basic,
            'supply' => $request->supply,
            'margin' => $request->margin,
            'basic_price' => $basic_price,
            'tax_value' => $request->vat,
            'tax_price' => $tax_price,
            'rsp' => ($basic_price + $tax_price),
            'effective_from' => Carbon::createFromFormat('d-m-Y', $request->effective_from),
            'effective_to' => $request->effective_to ? Carbon::createFromFormat('d-m-Y', $request->effective_to) : null,
            'updated_by' => Auth::id(),
        ]);

        // Insert or update Price histosy
        $price_data = Price::findOrFail($id);
        if($request->has('history')) {
            // Store data price history
            $new_price_history = PriceHistory::create([
                'price_id' => $id,
                'segment_id' => $price_data->segment_id,
                'district_id' => $price_data->district_id,
                'basic' => $request->basic,
                'supply' => $request->supply,
                'margin' => $request->margin,
                'basic_price' => $basic_price,
                'tax_value' => $request->vat,
                'tax_price' => $tax_price,
                'rsp' => ($basic_price + $tax_price),
                'effective_from' => Carbon::createFromFormat('d-m-Y', $request->effective_from),
                'effective_to' => $request->effective_to ? Carbon::createFromFormat('d-m-Y', $request->effective_to) : null,
                'created_by' => Auth::id(),
            ]);
        }
        else {
            // Get latest history record
            $ph = PriceHistory::where('price_id', $id)->latest()->first();
            // Update Price history
            $price_history_update = PriceHistory::where('id', $ph->id)->update([
                'basic' => $request->basic,
                'supply' => $request->supply,
                'margin' => $request->margin,
                'basic_price' => $basic_price,
                'tax_value' => $request->vat,
                'tax_price' => $tax_price,
                'rsp' => ($basic_price + $tax_price),
                'effective_from' => Carbon::createFromFormat('d-m-Y', $request->effective_from),
                'effective_to' => $request->effective_to ? Carbon::createFromFormat('d-m-Y', $request->effective_to) : null,
                'updated_by' => Auth::id(),
            ]);
        }

        // Responses
        return response()->json(['success' => 'Price record updated successfully!']);
    }
}