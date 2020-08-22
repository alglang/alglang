<?php

namespace App\Traits;

use App\Source;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait Sourceable
{
    public function addSource(Source $source): self
    {
        $this->sources()->attach($source);
        return $this;
    }

    public function sources(): MorphToMany
    {
        return $this->morphToMany(Source::class, 'sourceable');
    }
}
