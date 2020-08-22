<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VerbClass extends Model
{
    protected $primaryKey = 'abv';
    protected $keyType = 'str';
    public $incrementing = false;
}
