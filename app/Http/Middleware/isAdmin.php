<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use \Illuminate\Http\Response;
use \Illuminate\Http\RedirectResponse;
use Auth;

// use Illuminate\Http\RedirectResponse;
// use Illuminate\Http\JsonResponse;
class isAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->guard('web')) {
            return response()->json(['error' => 'unauthorized', 'status_code' => 400]);
        } else {
            return $next($request);
        }
    }
}