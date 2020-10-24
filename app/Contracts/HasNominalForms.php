<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * @property Collection $nominalForms
 * @property Collection $nominalGaps
 */
interface HasNominalForms
{
    public function nominalForms(): Relation;
    public function nominalGaps(): Relation;
}
