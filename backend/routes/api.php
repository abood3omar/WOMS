<?php

use App\Http\Controllers\ActivityLogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Security\SystemModuleController;
use App\Http\Controllers\Security\UsersController;
use App\Http\Controllers\Security\RoleRightsController;
use App\Http\Controllers\Security\SessionsController;
use App\Http\Controllers\WorkOrderController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::put('/profile/info', [App\Http\Controllers\ProfileController::class, 'updateInfo']);
    Route::put('/profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword']);
    Route::post('/profile/delete', [App\Http\Controllers\ProfileController::class, 'deleteAccount']);

    Route::get('/security/system-modules', [SystemModuleController::class, 'index'])->middleware('permission:system_modules,view');
    
    Route::post('/security/modules', [SystemModuleController::class, 'addModule'])->middleware('permission:system_modules,create');
    Route::put('/security/modules/{id}', [SystemModuleController::class, 'editModule'])->middleware('permission:system_modules,edit');
    Route::delete('/security/modules/{id}', [SystemModuleController::class, 'deleteModule'])->middleware('permission:system_modules,delete');

    Route::post('/security/actions', [SystemModuleController::class, 'addAction'])->middleware('permission:system_modules,create');
    Route::put('/security/actions/{id}', [SystemModuleController::class, 'editAction'])->middleware('permission:system_modules,edit');
    Route::delete('/security/actions/{id}', [SystemModuleController::class, 'deleteAction'])->middleware('permission:system_modules,delete');

    Route::post('/security/entities', [SystemModuleController::class, 'addEntity'])->middleware('permission:system_modules,create');
    Route::put('/security/entities/{id}', [SystemModuleController::class, 'editEntity'])->middleware('permission:system_modules,edit');
    Route::delete('/security/entities/{id}', [SystemModuleController::class, 'deleteEntity'])->middleware('permission:system_modules,delete');

    Route::post('/security/entities/{id}/actions', [SystemModuleController::class, 'updateEntityActions'])->middleware('permission:system_modules,edit');
    
    Route::get('/security/users', [UsersController::class, 'index'])->middleware('permission:users,view');
    Route::post('/security/users', [UsersController::class, 'store'])->middleware('permission:users,create');
    Route::put('/security/users/{id}', [UsersController::class, 'update'])->middleware('permission:users,edit');
    Route::delete('/security/users/{id}', [UsersController::class, 'destroy'])->middleware('permission:users,delete');
    Route::put('/security/users/{id}/password', [UsersController::class, 'changePassword'])->middleware('permission:users,edit');

    Route::get('/security/roles', [RoleRightsController::class, 'index'])->middleware('permission:role_rights,view');
    Route::post('/security/roles', [RoleRightsController::class, 'store'])->middleware('permission:role_rights,create');
    Route::put('/security/roles/{id}', [RoleRightsController::class, 'update'])->middleware('permission:role_rights,edit');
    Route::delete('/security/roles/{id}', [RoleRightsController::class, 'destroy'])->middleware('permission:role_rights,delete');

    Route::get('/work-orders', [WorkOrderController::class, 'index'])->middleware('permission:work_orders,view');
    Route::get('/work-orders/{id}', [WorkOrderController::class, 'show'])->middleware('permission:work_orders,view');
    Route::post('/work-orders', [WorkOrderController::class, 'store'])->middleware('permission:work_orders,create');
    Route::put('/work-orders/{id}/status', [WorkOrderController::class, 'updateStatus'])->middleware('permission:work_orders,change_status');
    Route::put('/work-orders/{id}/assign', [WorkOrderController::class, 'assign'])->middleware('permission:work_orders,assign'); 
    Route::put('/work-orders/{id}', [WorkOrderController::class, 'update'])->middleware('permission:work_orders,edit');
    Route::delete('/work-orders/{id}', [WorkOrderController::class, 'destroy'])->middleware('permission:work_orders,delete');

    Route::get('/logs', [ActivityLogController::class, 'index'])->middleware('permission:logs,view');
    Route::get('/dashboard/stats', [DashboardController::class, 'stats'])->middleware('permission:dashboard,view');

});