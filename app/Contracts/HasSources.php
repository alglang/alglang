<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * @property Collection $sources
 */
interface HasSources
{
    /**
     * @return Builder|Relation
     */
    public function sources();
}
