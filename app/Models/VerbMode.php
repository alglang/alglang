<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerbMode extends Model
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
}
