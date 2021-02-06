<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property Collection $sources
 */
interface HasSources
{
    /**
     * @return Builder|BelongsToMany
     */
    public function sources();
}
