<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;

/**
 * @property Collection $phonemes
 * @property Collection $vowels
 * @property Collection $consonants
 */
interface HasPhonemes
{
    public function phonemes(): Relation;

    public function vowels(): Relation;

    public function consonants(): Relation;
}
