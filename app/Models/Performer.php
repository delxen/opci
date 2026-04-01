<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Performer extends Model
{
    protected $fillable = [
        'source',
        'name',
        'type',
        'gender',
        'country',
        'disambiguation',
    ];

    public function aliases(): HasMany
    {
        return $this->hasMany(PerformerAlias::class);
    }

    public function identifiers(): HasMany
    {
        return $this->hasMany(PerformerIdentifier::class);
    }
}
