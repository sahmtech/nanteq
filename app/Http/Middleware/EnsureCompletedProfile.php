<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCompletedProfile
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()?->hasUnrestrictedAccess()) {
            return $next($request);
        }

        if(auth()->user()->profile_completion_status === "pending"){
            return response()->json(['success' => false, 'message' => __('api.uncompleted_profile_message')], 402);
        }
        return $next($request);
    }
}
