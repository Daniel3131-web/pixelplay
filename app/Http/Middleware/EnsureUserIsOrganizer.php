<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsOrganizer
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
 
        if (Auth::check() && Auth::user()->role === 'organizador') {
            return $next($request);
        }

        abort(403, 'Acesso não autorizado. Esta área é exclusiva para organizadores.');
        
        // return redirect('/')->with('error', 'Acesso restrito.');
    }
}
