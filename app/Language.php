<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    public function getUrlAttribute()
    {
        return route('languages.show', ['language' => $this], false);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
