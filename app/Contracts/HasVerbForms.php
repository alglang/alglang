<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @property Collection $verbForms
 * @property Collection $verbGaps
 */
interface HasVerbForms
{
    /** @return HasMany|MorphToMany */
    public function verbForms();

    /** @return HasMany|MorphToMany */
    public function verbGaps();
}
