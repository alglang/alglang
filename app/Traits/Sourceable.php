<?php

namespace App\Traits;

use App\Models\Source;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait Sourceable
{
    public function addSource(Source $source, array $pivotFields = []): self
    {
        $this->sources()->attach($source, $pivotFields);
        return $this;
    }

    public function sources(): BelongsToMany
    {
        return $this->morphToMany(Source::class, 'sourceable')
                    ->as('attribution')
                    ->withPivot('extra_info');
    }
}
