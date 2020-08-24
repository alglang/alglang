<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VerbClass extends Model
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

    public $timestamps = false;
}
