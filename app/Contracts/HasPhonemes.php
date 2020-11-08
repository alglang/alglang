<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;

/**
 * @property Collection $phonoids
 * @property Collection $phonemes
 * @property Collection $vowels
 * @property Collection $consonants
 * @property Collection $clusters
 */
interface HasPhonemes
{
    public function phonoids(): Relation;

    public function phonemes(): Relation;

    public function vowels(): Relation;

    public function consonants(): Relation;

    public function clusters(): Relation;
}
