<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use App\Models\ActivityLog;
use App\Models\WorkOrderLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = WorkOrder::with(['creator:id,name', 'assignee:id,name'])->latest();

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")         
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('id', $search);                       
            });
        }

        return response()->json($query->paginate(10));
    }

    public function show($id)
    {
        $order = WorkOrder::with(['creator', 'assignee', 'logs.performer'])->findOrFail($id);
        return response()->json($order);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'priority' => 'required|in:low,medium,high',
        ]);

        $order = WorkOrder::create([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'status' => 'pending',
            'created_by' => Auth::id(),
            'assigned_to' => $request->assigned_to,
            'due_date'    => $request->due_date,
        ]);
        
        $this->logAction($order->id, "Work Order Created");
        return response()->json($order);
    }

    public function updateStatus(Request $request, $id)
    {
        $order = WorkOrder::findOrFail($id);
        $order->update(['status' => $request->status]);
        $this->logAction($id, "Status changed to {$request->status}");
        return response()->json($order);
    }

    public function assign(Request $request, $id)
    {
        $order = WorkOrder::findOrFail($id);
        $order->update(['assigned_to' => $request->assigned_to]);
        $this->logAction($id, "Assigned to user #{$request->assigned_to}");
        return response()->json($order);
    }

    public function update(Request $request, $id)
    {
        $order = WorkOrder::findOrFail($id);

        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority'    => 'required|in:low,medium,high',
            'due_date'    => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $order->update([
            'title'       => $request->title,
            'description' => $request->description,
            'priority'    => $request->priority,
            'due_date'    => $request->due_date,
            'assigned_to' => $request->assigned_to,
        ]);

        $this->logAction($order->id, "Work order details updated");

        return response()->json(['message' => 'Order updated successfully', 'data' => $order]);
    }

    public function destroy($id)
    {
        $order = WorkOrder::findOrFail($id);
        $this->logAction($order->id, "Work Order Deleted");
        $order->delete();
        return response()->json(['message' => 'Order deleted successfully']);
    }

    private function logAction($workOrderId, $action)
    {
        WorkOrderLog::create([
            'work_order_id' => $workOrderId,
            'action'        => $action,
            'performed_by'  => Auth::id(),
        ]);

        ActivityLog::create([
            'user_id'       => Auth::id(),
            'action'        => $action,
            'details'       => "Action performed on Work Order #{$workOrderId}",
            'ip_address'    => request()->ip(),
            'work_order_id' => $workOrderId
        ]);
    }
}