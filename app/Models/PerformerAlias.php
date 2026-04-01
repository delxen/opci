<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerformerAlias extends Model
{
    protected $fillable = [
        'performer_id',
        'name',
        'locale',
        'primary',
    ];

    protected function casts(): array
    {
        return [
            'primary' => 'boolean',
        ];
    }

    public function performer(): BelongsTo
    {
        return $this->belongsTo(Performer::class);
    }
}
