<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function stats()
    {
        $totalOrders = WorkOrder::count();
        $pendingOrders = WorkOrder::where('status', 'pending')->count();
        $completedOrders = WorkOrder::where('status', 'completed')->count();
        $inProgressOrders = WorkOrder::where('status', 'in_progress')->count();
        $totalUsers = User::count();

        $recentActivity = ActivityLog::with('user:id,name')
            ->latest()
            ->take(5)
            ->get();

        return response()->json([
            'counts' => [
                'total' => $totalOrders,
                'pending' => $pendingOrders,
                'completed' => $completedOrders,
                'in_progress' => $inProgressOrders,
                'users' => $totalUsers
            ],
            'recent_activity' => $recentActivity
        ]);
    }
}