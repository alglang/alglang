<?php

namespace App\Traits;

use App\Source;

trait Sourceable
{
    public function addSource(Source $source)
    {
        $this->sources()->attach($source);
    }

    public function sources()
    {
        return $this->morphToMany(Source::class, 'sourceable');
    }
}
