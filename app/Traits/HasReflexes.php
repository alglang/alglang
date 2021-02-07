<?php

namespace App\Traits;

use App\Models\Reflex;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;

trait HasReflexes
{
    public function parents(): Relation
    {
        return $this->belongsToMany(self::class, 'reflexes', 'reflex_id', 'phoneme_id')
                    ->using(Reflex::class)
                    ->withPivot('environment');
    }

    public function children(): Relation
    {
        return $this->belongsToMany(self::class, 'reflexes', 'phoneme_id', 'reflex_id')
                    ->using(Reflex::class)
                    ->withPivot('environment');
    }

    public function parentsFromLanguage(string $languageCode): Collection
    {
        return $this->relationsFromLanguage($languageCode, 'parents');
    }

    public function childrenFromLanguage(string $languageCode): Collection
    {
        return $this->relationsFromLanguage($languageCode, 'children');
    }

    protected function relationsFromLanguage(string $languageCode, string $relation): Collection
    {
        $stack = collect([$this]);
        $langRelations = collect();

        while (!$stack->isEmpty()) {
            $curr = $stack->pop();

            foreach ($curr->$relation as $related) {
                if ($related->language_code === $languageCode) {
                    $langRelations->push($related);
                } else {
                    $stack->push($related);
                }
            }
        }

        return $langRelations;
    }

    public function getPaParentsAttribute(): Collection
    {
        return $this->parentsFromLanguage('PA');
    }
}
