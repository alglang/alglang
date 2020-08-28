<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerbClass extends Model
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
}
