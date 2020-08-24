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

    public $incrementing = false;

    public $timestamps = false;

    protected $primaryKey = 'abv';

    protected $keyType = 'str';

    protected $appends = ['url'];

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
