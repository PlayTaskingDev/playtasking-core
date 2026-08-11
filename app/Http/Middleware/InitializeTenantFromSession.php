<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InitializeTenantFromSession
{
    public function handle(Request $request, Closure $next): Response
    {
        if (session()->has('active_tenant')) {

            $tenant = Tenant::find(session('active_tenant'));

            if ($tenant) {

                tenancy()->initialize($tenant);
            }
        }

        return $next($request);
    }
}