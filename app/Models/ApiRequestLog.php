<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiRequestLog extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $fillable = [
        'method',
        'endpoint',
        'tenant',
        'client_id',
        'ip_address',
        'status_code',
        'duration_ms',
        'request_data',
        'response_body',
        'requested_at',
    ];

    protected $casts = [
        'request_data' => 'array',
        'requested_at' => 'datetime',
    ];
}
