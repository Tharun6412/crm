<?php

namespace App\Http\Controllers\Master\Invoice;

use App\Http\Controllers\Controller;
use App\Models\Master\BillAddress;
use App\Models\Master\District;
use App\Models\Master\Ga;
use App\Models\Master\State;
use Illuminate\Http\Request;

class InvoiceAddressController extends Controller
{
    /**
     * Index
     */
    public function index()
    {
        // Get Invoice address
        $addresses = BillAddress::all();

        // Render output
        return view('master.invoice.address.list', ['addresses' => $addresses]);
    }

    /**
     * Show
     */
    public function show($id)
    {
        // Get address details
        $address = BillAddress::find($id);

        // Render output
        return view('master.invoice.address.show', ['address' => $address]);
    }

    /**
     * Edit
     */
    public function edit($id)
    {
        // Get address details
        $address = BillAddress::find($id);
        $districts = District::where('state_id', $address->state_id)->get();

        // Render output
        return view('master.invoice.address.edit', [
            'address' => $address,
            'districts' => $districts,
        ]);
    }

    /**
     * Update
     */
    public function update(Request $request, $id)
    {
        // Validation
        $request->validate([
            'line1' => 'required',
            'line2' => 'required',
            'city' => 'required',
            'district_id' => 'required',
            'pincode' => 'required',
        ]);

        // Update
        $update_address = BillAddress::where('id', $id)->update([
            'line1' => $request->line1,
            'line2' => $request->line2,
            'city' => $request->city,
            'district_id' => $request->district_id,
            'pincode' => $request->pincode,
        ]);

        // Response
        return response()->json(['success' => 'Address detail updated successfully!']);
    }
}