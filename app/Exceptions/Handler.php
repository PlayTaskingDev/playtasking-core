<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Sentry\Laravel\Integration;
use Throwable;
use Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedOnDomainException;
use Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedByPathException;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
         TenantCouldNotBeIdentifiedOnDomainException::class,
        TenantCouldNotBeIdentifiedByPathException::class,
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            if (app()->bound('sentry')) {
                app('sentry')->captureException($e);
            }
        });

        $this->reportable(function (\Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedException $e) {
        //11-10-25 AP
        if (request()->is(['dns-query', 'apple-touch-icon*', 'favicon.ico', 'robots.txt','dashboard'])) {
            return false;
        }
	if (filter_var(request()->getHost(), FILTER_VALIDATE_IP)) {
            return false;
        }
	});
    }
}
