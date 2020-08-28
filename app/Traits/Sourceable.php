<?php

namespace App\Traits;

use App\Source;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait Sourceable
{
    public function addSource(Source $source, array $pivotFields = []): self
    {
        $this->sources()->attach($source, $pivotFields);
        return $this;
    }

    public function sources(): MorphToMany
    {
        return $this->morphToMany(Source::class, 'sourceable')
                    ->as('attribution')
                    ->withPivot('extra_info');
    }
}
