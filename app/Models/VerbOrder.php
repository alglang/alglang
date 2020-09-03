<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class VerbOrder extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Configuration
    |--------------------------------------------------------------------------
    |
    */

    public $incrementing = false;

    public $timestamps = false;

    protected $primaryKey = 'name';

    protected $keyType = 'str';

    /*
    |--------------------------------------------------------------------------
    | Protected methods
    |--------------------------------------------------------------------------
    |
    */

    protected static function booted()
    {
        static::addGlobalScope('order', function (Builder $query) {
            return $query->orderBy('order_key');
        });
    }
}
