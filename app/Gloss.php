<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gloss extends Model
{
    protected $guarded = [];
    protected $primaryKey = 'abv';
    protected $keyType = 'str';
}
