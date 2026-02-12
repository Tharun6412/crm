<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\ApiKey;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ApiKeyController extends Controller
{
    /**
     * Index
     */
    public function index()
    {
        // Get all API Keys
        $api_keys = ApiKey::all();

        // Render output
        return view('admin.api-keys.list', ['api_keys' => $api_keys]);
    }

    /**
     * Edit
     */
    public function edit($id)
    {
        $key_details = ApiKey::find($id);

        // Render Output
        return view('admin.api-keys.edit', ['key' => $key_details]);
    }

    /**
     * Update API key details
     */
    public function update(Request $request, $id)
    {
        // Validate
        $request->validate([
            'name' => 'required',
            'expires_at' => 'required',
            'status' => 'required',
        ]);

        // Update
        $update_api_key = ApiKey::where('id', $id)->update([
            'name' => $request->name,
            'expires_at' => Carbon::createFromFormat('d-m-Y', $request->expires_at),
            'is_active' => $request->status,
        ]);

        // Response
        return response()->json(['success' => 'API KEY details updated!']);
    }

    /**
     * Delete key
     */
    public function destroy($id)
    {
        // Delete the key
        ApiKey::destroy($id);

        // Response
        return response()->json(['msg' => 'API Key deleted successfully!']);
    }
}