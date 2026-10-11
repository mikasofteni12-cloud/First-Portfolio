<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(! $request->user() || $request->user()->role !== 'owner'){
            return redirect('/')->with('error', 'Access denied. Owner account required.');
        }
        return $next($request);
    }
}
