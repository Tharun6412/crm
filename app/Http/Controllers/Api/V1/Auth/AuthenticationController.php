<?php 

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthenticationRequest;
use App\Models\Admin\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{
    /**
     * Login
     */
    public function login(AuthenticationRequest $request)
    {
        $request->authenticate();
        $user = $request->user();
        // Create a new token for API access
        $token = $user->createToken('api-token')->plainTextToken;

        // Get user roles and app modules
        $app_modules = $user->roles->flatMap->appModules->unique('id')->values()->map->only(['id', 'name', 'code'])->toArray();

        return response()->json([
            'token' => $token,
            'user' => $user->makeHidden(['roles']),
            'user_gas' => $user->ga()->select('name', 'ga_id')->get()->makeHidden('pivot'),
            'roles' => $user->roles()->select('adm_roles.id', 'adm_roles.name')->get()->makeHidden('pivot'),
            'modules' => $app_modules,
        ]);
    }

    /**
     * Profile
     */
    public function profile(Request $request)
    {
        return $request->user();
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}