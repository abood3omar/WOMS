<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $fields['email'])->first();
        if(!$user)
        {
            return response()->json([
                'message' => 'This email is not registered in our system.'
            ], 401);
        }
   

        if(!Hash::check($fields['password'], $user->password))
        {
            return response()->json([
                'message' => 'Incorrect password. Please try again.'
            ], 401);
        }

        Auth::login($user);
        $token = $user->createToken('auth_token')->plainTextToken;

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'User Logged In',
            'details' => 'User logged in successfully',
            'ip_address' => $request->ip(),
        ]);
 
        $permissions = [];
        if ($user->RoleID) {
            $permissions = DB::table('rolesrights')
                ->join('entities', 'rolesrights.entity_id', '=', 'entities.EntityID')
                ->join('actions', 'rolesrights.action_id', '=', 'actions.ActionID')
                ->where('rolesrights.role_id', $user->RoleID)
                ->select(DB::raw("CONCAT(entities.EntityName, '.', actions.ActionName) as permission_key"))
                ->pluck('permission_key')
                ->toArray();
        }

        return response()->json([
            'message' => 'Login success',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role ? $user->role->RoleName : 'User',
                'permissions' => $permissions,
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }
}