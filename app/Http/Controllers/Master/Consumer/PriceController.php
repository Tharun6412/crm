<?php

namespace App\Http\Controllers\Master\Consumer;

use App\Http\Controllers\Controller;
use App\Models\Master\Price;

class PriceController extends Controller
{
    /**
     * Index
     */
    public function index()
    {
        // Get prices and show
        $gas_prices = Price::all();
        return view('master.consumer.prices.list', ['gas_prices' => $gas_prices]);
    }
}