<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * @property Collection $verbForms
 * @property Collection $verbGaps
 */
interface HasVerbForms
{
    public function verbForms(): Relation;
    public function verbGaps(): Relation;
}
