<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\WorkOrderLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user:id,name');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")  
                  ->orWhere('details', 'like', "%{$search}%")     
                  ->orWhere('ip_address', 'like', "%{$search}%")  
                  ->orWhereHas('user', function ($u) use ($search) { 
                      $u->where('name', 'like', "%{$search}%");
                  });
            });
        }

        return response()->json($query->latest()->paginate(20));
    }
}