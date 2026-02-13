<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request):+
     */
    public function handle(Request $request, Closure $next, $entityName, $actionName): Response
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $user = Auth::user();

        if (!$user->hasPermission($entityName, $actionName)) {
            return response()->json(['message' => 'User does not have the right permissions.'], 403);
        }

        return $next($request);
    }
}
