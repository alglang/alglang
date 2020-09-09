<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * @property Collection $nominalForms
 */
interface HasNominalForms
{
    public function nominalForms(): Relation;
}
