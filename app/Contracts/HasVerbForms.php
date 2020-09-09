<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * @property Collection $verbForms
 */
interface HasVerbForms
{
    public function verbForms(): Relation;
}
