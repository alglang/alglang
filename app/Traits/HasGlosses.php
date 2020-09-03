<?php

namespace App\Traits;

use App\Models\Gloss;
use Illuminate\Support\Collection;

trait HasGlosses
{
    /** @var Collection */
    private $glosses_;

    public function getGlossesAttribute(): Collection
    {
        $this->glosses_ = $this->glosses_ ?? $this->buildGlosses();
        return $this->glosses_;
    }

    protected function buildGlosses(): Collection
    {
        $abvs = collect(explode('.', $this->gloss));
        $existing = Gloss::find($abvs);

        return $abvs->map(fn ($abv) => $existing->firstWhere('abv', $abv) ?? new Gloss(['abv' => $abv]));
    }
}
