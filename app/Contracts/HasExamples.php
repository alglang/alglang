<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;

/**
 * @property Collection $examples
 */
interface HasExamples
{
    /**
     * @return Builder|HasMany|MorphToMany
     */
    public function examples();
}
