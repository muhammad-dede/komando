<?php

namespace App\Http\Middleware;

use Closure;

class VerifikatorAssessment
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
        if(!($request->user()->verifikatorAssessment->count()>0 || $request->user()->can('report_assessment'))){
            return redirect()->back()->with('warning', 'You are not authorized');
        }

        return $next($request);
    }
}
