<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VerbOrder extends Model
{
    protected $primaryKey = 'name';
    protected $keyType = 'str';
    public $incrementing = false;
}
