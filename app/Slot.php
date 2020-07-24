<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Slot extends Model
{
    protected $primaryKey = 'abv';
    protected $keyType = 'str';
    public $incrementing = false;

    /**
     * @var array<string>
     */
    protected $appends = ['url'];

    /**
     * @return string
     */
    public function getUrlAttribute()
    {
        return route('slots.show', $this, false);
    }
}
