<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Models\Action;
use App\Models\Entity;
use App\Models\Module;
use Illuminate\Http\Request;

class SystemModuleController extends Controller
{
    public function index()
    {
        return response()->json([
            'modules'  => Module::all(),
            'actions'  => Action::all(),
            'entities' => Entity::with(['module', 'actions'])->get()
        ]);
    }

    // --- Modules ---
    public function addModule(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:modules,ModuleName']);
        $module = Module::create(['ModuleName' => $request->name]);
        return response()->json(['message' => 'Module added successfully', 'data' => $module]);
    }

    public function editModule($id, Request $request)
    {
        $request->validate(['name' => 'required|string|unique:modules,ModuleName,' . $id . ',ModuleID']);
        $module = Module::findOrFail($id);
        $module->update(['ModuleName' => $request->name]);
        return response()->json(['message' => 'Module updated successfully', 'data' => $module]);
    }

    public function deleteModule($id)
    {
        Module::findOrFail($id)->delete();
        return response()->json(['message' => 'Module deleted successfully']);
    }

    // --- Actions ---
    public function addAction(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:actions,ActionName']);
        $action = Action::create(['ActionName' => $request->name]);
        return response()->json(['message' => 'Action added successfully', 'data' => $action]);
    }

    public function editAction($id, Request $request)
    {
        $request->validate(['name' => 'required|string|unique:actions,ActionName,' . $id . ',ActionID']);
        $action = Action::findOrFail($id);
        $action->update(['ActionName' => $request->name]);
        return response()->json(['message' => 'Action updated successfully', 'data' => $action]);
    }

    public function deleteAction($id)
    {
        Action::findOrFail($id)->delete();
        return response()->json(['message' => 'Action deleted successfully']);
    }

    // --- Entities ---
    public function addEntity(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|unique:entities,EntityName',
            'module_id' => 'required|exists:modules,ModuleID',
        ]);
        
        $entity = Entity::create([
            'EntityName' => $request->name,
            'ModuleID'   => $request->module_id,
        ]);
        
        $entity->load('module'); 

        return response()->json(['message' => 'Entity added successfully', 'data' => $entity]);
    }

    public function editEntity($id, Request $request)
    {
        $request->validate([
            'name'      => 'required|string|unique:entities,EntityName,' . $id . ',EntityID',
            'module_id' => 'required|exists:modules,ModuleID',
        ]);

        $entity = Entity::findOrFail($id);
        $entity->update([
            'EntityName' => $request->name,
            'ModuleID'   => $request->module_id,
        ]);

        return response()->json(['message' => 'Entity updated successfully', 'data' => $entity->load('module')]);
    }

    public function deleteEntity($id)
    {
        Entity::findOrFail($id)->delete();
        return response()->json(['message' => 'Entity deleted successfully']);
    }

    public function updateEntityActions($id, Request $request)
    {
        $request->validate([
            'actions' => 'present|array',
            'actions.*' => 'exists:actions,ActionID'
        ]);

        try {

            $entity = Entity::findOrFail($id);
            $entity->actions()->sync($request->actions);

            return response()->json(['message' => 'Actions linked successfully']);
            
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error linking actions: ' . $e->getMessage()], 500);
        }
    }

}