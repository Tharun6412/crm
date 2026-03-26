<?php
namespace App\Http\Controllers\Api\prepaid;

use App\Actions\Prepaid\ConsumerActivation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SmartConnect 
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'crn' => 'required',
            'move_in_read' => 'required',
            'move_in_date' => 'required|date',
            'meter_no' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'error' => $validator->errors()->first(),
                'response' => null
            ], 400);
        }

        $response = ConsumerActivation::process($request->all());
        return response()->json($response, $response['code']);
    }
}