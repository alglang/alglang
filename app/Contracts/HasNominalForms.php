<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @property Collection $nominalForms
 * @property Collection $nominalGaps
 */
interface HasNominalForms
{
    /** @return HasMany|MorphToMany */
    public function nominalForms();

    /** @return HasMany|MorphToMany */
    public function nominalGaps();
}
