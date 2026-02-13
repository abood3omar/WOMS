<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Models\Action;
use App\Models\Module;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleRightsController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();

        $modules = Module::with(['entities' => function($q) {
            $q->with('actions'); 
        }])->get();

        return response()->json([
            'roles'   => $roles,
            'modules' => $modules
        ]);
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:roles,RoleName']);
        $role = Role::create(['RoleName' => $request->name]);
        return response()->json(['message' => 'Role created successfully', 'data' => $role]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,RoleName,' . $id . ',RoleID',
            'permissions' => 'nullable|array' 
        ]);

        $role = Role::findOrFail($id);
        $role->update(['RoleName' => $request->name]);
        $role->permissions()->detach();

        if ($request->has('permissions')) {
             foreach ($request->permissions as $perm) {
                $role->permissions()->attach($perm['entity_id'], ['action_id' => $perm['action_id']]);
            }
        }

        return response()->json(['message' => 'Role and rights updated successfully']);
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->permissions()->detach();
        $role->delete();
        return response()->json(['message' => 'Role deleted successfully']);
    }
}