<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * @property Collection $morphemes
 */
interface HasMorphemes
{
    public function morphemes(): Relation;
}
