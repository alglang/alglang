<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerbClass extends Model
{
    use HasFactory;

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
