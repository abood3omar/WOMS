<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('role');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('role', function ($q) use ($search) {
                      $q->where('RoleName', 'like', "%{$search}%");
                  });
            });
        }

        $query->orderBy('id', 'asc');

        return response()->json($query->paginate(10));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'RoleID'   => 'nullable|exists:roles,RoleID',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'RoleID'        => $request->RoleID,
            'password'      => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'User created successfully', 'user' => $user]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|unique:users,email,' . $id,
            'RoleID' => 'nullable|exists:roles,RoleID',
        ]);

        $user->update([
            'name'   => $request->name,
            'email'  => $request->email,
            'RoleID' => $request->RoleID,
        ]);

        return response()->json(['message' => 'User updated successfully']);
    }

    public function changePassword(Request $request, $id)
    {
        $request->validate(['new_password' => 'required|min:6|confirmed']);
        $user = User::findOrFail($id);
        $user->update(['password' => Hash::make($request->new_password)]);
        return response()->json(['message' => 'Password changed successfully']);
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
}
