<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gloss extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Configuration
    |--------------------------------------------------------------------------
    |
    */

    public $incrementing = false;

    protected $guarded = [];

    protected $primaryKey = 'abv';

    protected $keyType = 'str';

    protected $appends = ['url'];

    /*
    |--------------------------------------------------------------------------
    | Attribute accessors
    |--------------------------------------------------------------------------
    |
    */

    public function getUrlAttribute(): ?string
    {
        if ($this->exists) {
            return route('glosses.show', $this, false);
        }

        return null;
    }
}
