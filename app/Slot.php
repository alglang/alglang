<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Slot extends Model
{
    protected $primaryKey = 'abv';
    protected $keyType = 'str';
    public $incrementing = false;

    protected $appends = ['url'];

    public function getUrlAttribute()
    {
        return "/slots/$this->abv";
    }
}
