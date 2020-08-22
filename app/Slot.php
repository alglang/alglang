<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Slot extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Configuration
    |--------------------------------------------------------------------------
    |
    */

    protected $primaryKey = 'abv';

    protected $keyType = 'str';

    public $incrementing = false;

    protected $appends = ['url'];

    public $timestamps = false;

    /*
    |--------------------------------------------------------------------------
    | Attribute accessors
    |--------------------------------------------------------------------------
    |
    */

    public function getUrlAttribute(): string
    {
        return route('slots.show', $this, false);
    }
}
