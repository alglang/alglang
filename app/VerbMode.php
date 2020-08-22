<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VerbMode extends Model
{
    protected $primaryKey = 'name';
    protected $keyType = 'str';
    public $incrementing = false;
}
