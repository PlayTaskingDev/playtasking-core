<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ApiRequestLog;

class LogApiRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
     public function handle(Request $request, Closure $next): Response
    {
      
        $start = microtime(true);

        $response = $next($request);

        $durationMs = round(
            (microtime(true) - $start) * 1000
        );

        ApiRequestLog::create([
            'method' => $request->method(),

            'endpoint' => $request->path(),

            'tenant' => $request->header('X-Tenant'),

            'client_id' => optional($request->user())->id,

            'ip_address' => $request->ip(),

            'status_code' => $response->getStatusCode(),

            'duration_ms' => $durationMs,

            'request_data' => $request->except([
                'password',
                'token',
            ]),

            'response_body' => $response->getContent(),

            'requested_at' => now(),
        ]);

        return $response;
    }
}
