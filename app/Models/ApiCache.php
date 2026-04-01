<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiCache extends Model
{
    protected $table = 'api_cache';

    protected $fillable = [
        'source',
        'endpoint',
        'response',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }
}
