<?php
namespace App\Http\Controllers\Api\prepaid;

use App\Actions\Prepaid\MroPushAction;
use Illuminate\Http\Request;

class MroPush 
{
    public function store(Request $request)
    {
        $payload = $request->all();
        if(empty($payload)){
            return response()->json([
                'status' => false,
                'data' => ['mro_response' => [
                        [
                            'error_code' => 1,
                            'message' => 'No data received'
                        ]
                    ]
                ]
            ],400);
        }
        $response = MroPushAction::process($payload);
        return response()->json([
            'status' => true,
            'data' => $response,
            'response' => null
        ]);
    
    }
}