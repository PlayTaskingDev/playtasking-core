<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class ApiClient extends Model
{
    use HasApiTokens;

    protected $fillable = [
        'name',
        'company',
        'active',
        'description',
        'last_used_at',
    ];

    public function tenants()
    {
        return $this->belongsToMany(
            Tenant::class,
            'api_client_tenant',
            'api_client_id',
            'tenant_id'
        );
    }

    public static function createWithToken(array $data, array $tenantIds): array
    {
        $client = self::create($data);
        $client->tenants()->attach($tenantIds);
        $token = $client->createToken('API Principal')->plainTextToken;
        $client->load('tenants');
        return [
            'client' => $client,
            'token' => $token,
        ];
    }
}
