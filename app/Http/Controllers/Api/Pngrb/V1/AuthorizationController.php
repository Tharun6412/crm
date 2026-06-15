<?php

namespace App\Http\Controllers\Api\Pngrb\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Customized OAuth 2.0 authorization implementation
 * for the PNGRB Unified Portal.
 * 
 * Extends Passport authentication to provide
 * customized token validation and API responses
 */
class AuthorizationController extends Controller
{
    /**
     * Authorization request handler
     */
    public function authorize(Request $request)
    {
        // Validations
        $validator = Validator::make($request->all(), [
            'grant_type' => 'required|in:client_credentials',
            'client_id' => 'required',
            'client_secret' => 'required',
        ]);

        // Check validations
        if ($validator->fails()) {
            // Custom error message
            $error_data = [];
            foreach ($validator->errors()->messages() as $field => $messages) {
                foreach ($messages as $message) {
                    $error_data[] = [
                        'field' => $field,
                        'message' => $message,
                    ];
                }
            }
            return response()->json([
                'success' => false,
                'statusCode' => 400,
                'message' => 'Validation failed.',
                'errors' => $error_data,
            ], 400);
        }

        // Generate Token using default passport route
        $passportRequest = Request::create('/oauth/token', 'POST', [
            'grant_type'    => 'client_credentials',
            'client_id'     => $request->client_id,
            'client_secret' => $request->client_secret,
        ]);

        // Handle response
        $response = app()->handle($passportRequest);
        
        $data = json_decode($response->getContent(), true);

        // Check the response code
        if ($response->getStatusCode() !== 200) {
            // Custom error message
            return response()->json([
                'success' => false,
                'statusCode' => $response->getStatusCode(),
                'message' => $data['error_description'] ?? 'Unauthorized',
                'error' => [
                    'field' => 'client_credentials',
                    'message' => $data['error'] ?? null,
                ],
            ], $response->getStatusCode());
        }

        // Return token details
        return response()->json([
            'status' => true,
            'access_token' => $data['access_token'] ?? '',
            'expires_in' => $data['expires_in'] ?? '',
            'token_type' => $data['token_type'] ?? '',
        ]);
    }
}