<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gloss extends Model
{
    protected $guarded = [];

    protected $primaryKey = 'abv';
    protected $keyType = 'str';
    public $incrementing = false;

    protected $appends = ['url'];

    public function getUrlAttribute()
    {
        if ($this->exists) {
            return route('glosses.show', $this, false);
        }
    }
}
