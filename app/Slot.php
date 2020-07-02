<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Slot extends Model
{
    protected $appends = ['url'];

    public function getUrlAttribute()
    {
        return "/slots/$this->abv";
    }
}
