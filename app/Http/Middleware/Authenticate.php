<?php

namespace App\Http\Middleware;

use App\Helpers\ValidateCV;
use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
//                return redirect()->guest('login');
//                return redirect()->guest('auth/login')->with('error','Please login first.');
                return redirect()->guest('auth/login');
            }
        }

        // check if validasi CV enabled
        if (env('VALIDASI_CV_ENABLE', false)) {
            $validasi = new ValidateCV();
            $validasi->validate(Auth::user());
        }

        return $next($request);
    }
}
