<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;

/**
 * @property Collection $examples
 */
interface HasExamples
{
    /**
     * @return Builder|Relation
     */
    public function examples();
}
