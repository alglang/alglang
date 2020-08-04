<?php

namespace App\Traits;

use App\Source;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait Sourceable
{
    public function addSource(Source $source): void
    {
        $this->sources()->attach($source);
    }

    public function sources(): MorphToMany
    {
        return $this->morphToMany(Source::class, 'sourceable');
    }
}
