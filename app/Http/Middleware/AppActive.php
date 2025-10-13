<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AppActive
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
        if ( !get_app_setting('app_active') ) {
            return redirect()->route('app.inactive', ['tenant' => tenant('id')]);
        }
        
        return $next($request);
    }
}
