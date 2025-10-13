<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    private $unwantedHeaders = ['X-Powered-By', 'server', 'Server', 'Set-Cookie'];
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        
        $response = $next($request);

        if (!app()->environment('testing')) {
            $response->headers->set('Referrer-Policy', 'no-referrer-when-downgrade');
            $response->headers->set('X-XSS-Protection', '1; mode=block');
            $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
            $response->headers->set('X-Content-Type-Options', 'nosniff');
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
            $response->headers->set('Content-Security-Policy', "default-src 'self' 127.0.0.1:5173 127.0.0.1:8000 ws://127.0.0.1:5173 unpkg.com fonts.bunny.net *.youtube.com *.vimeo.com storage.googleapis.com 'unsafe-inline' 'unsafe-eval' data: blob:;");
            $response->headers->set('Expect-CT', 'enforce, max-age=30');
            $response->headers->set('Permissions-Policy', 'autoplay=(self), camera=(), encrypted-media=(self), fullscreen=(), geolocation=(self), gyroscope=(self), magnetometer=(), microphone=(), midi=(), payment=(), sync-xhr=(self), usb=()');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'GET,POST,PUT,PATCH,DELETE,OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type,Authorization,X-Requested-With,X-CSRF-Token');

            $this->removeUnwantedHeaders($this->unwantedHeaders);
        }

        return $response;
        //return $next($request);
    }

    private function removeUnwantedHeaders($headers): void
    {
        foreach ($headers as $header) {
            header_remove($header);
        }
    }
}
