<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Tenant;
use App\Models\ApiClient;

class AuthenticateApiClient
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $client = $request->user();
        $tenantId = $request->header('X-Tenant');

        if (! $tenantId) {
            return response()->json(['message' => 'Header X-Tenant requerido.'], 400);
        }

        if (! $client->tenants()->where('tenants.id', $tenantId)->exists()) {
            return response()->json(['message' => 'No autorizado para este tenant.'], 403);
        }

        $tenant = Tenant::find($tenantId);
        if (! $tenant) {
            return response()->json(['message' => 'Tenant no encontrado.'], 404);
        }

        tenancy()->initialize($tenant);

        return $next($request);
    }
}
