<?php

namespace App\Http\Middleware;

use Closure;

class VerifyGM
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
        if(!$request->user()->isGM()){
            return redirect('/')->with('error', 'You are not authorized');
        }

        return $next($request);
    }
}
