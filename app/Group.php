<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    public function getUrlAttribute()
    {
        return route('groups.show', ['group' => $this], false);
    }
}
