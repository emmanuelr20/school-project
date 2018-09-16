<?php

namespace App\Http\Middleware;

use Closure;

class SuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (\Auth::check() && \Auth::user()->isSuperAdmin()) {
            return $next($request);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Authorized Access: Super Admin access privilege needed.'
        ], 401);
    }
}
