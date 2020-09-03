<?php

namespace App\Traits;

use App\Models\Source;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

trait AggregatesSources
{
    /** @var int */
    public $sources_count;

    /** @var Collection */
    private $sources_;

    public function getSourcesAttribute(): Collection
    {
        $this->sources_ = $this->sources_ ?? $this->sources()->get();
        return $this->sources_;
    }

    public function sources(): Builder
    {
        return Source::where(function (Builder $query) {
            foreach ($this->getSourcedRelations() as $relation) {
                $query->orWhereHas($relation, fn ($query) => $query->where('language_code', $this->code));
            }
        });
    }

    public function loadSourcesCount(): void
    {
        $this->sources_count = $this->sources()->count();
    }

    public function getSourcedRelations(): array
    {
        return $this->sourcedRelations ?? [];
    }
}
