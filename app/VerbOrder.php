<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VerbOrder extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Configuration
    |--------------------------------------------------------------------------
    |
    */

    protected $primaryKey = 'name';

    protected $keyType = 'str';

    public $incrementing = false;

    public $timestamps = false;
}
