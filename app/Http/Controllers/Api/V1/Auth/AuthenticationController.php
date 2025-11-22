<?php 

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{
    /**
     * Login
     */
    public function login(Request $request)
    {
        $request->validate([
            'emp_id' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('emp_id', $request->emp_id)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Create a new token for API access
        $token = $user->createToken('api-token')->plainTextToken;

        // Get user roles and app modules
        $roles = $user->roles->pluck('id')->toArray();
        $app_modules = $user->roles->flatMap(function ($role) {
            return $role->appModules;
        })->unique('id')->pluck('code')->toArray();

        return response()->json([
            'token' => $token,
            'user' => $user,
            'roles' => $roles,
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