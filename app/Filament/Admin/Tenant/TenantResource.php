<?php

namespace App\Filament\Admin\Tenant;

use App\Models\Tenant;
use Filament\Resources\Resource;

abstract class TenantResource extends Resource
{
    protected static function initializeTenant(): void
    {
        if (! session()->has('active_tenant')) {
            return;
        }

        $tenant = Tenant::find(session('active_tenant'));

        if (! $tenant) {
            return;
        }

        tenancy()->initialize($tenant);
    }
}