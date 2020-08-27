<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;

trait HasParent
{
    public function parent(): Relation
    {
        return $this->belongsTo(self::class);
    }

    public function children(): Relation
    {
        return $this->hasMany(self::class, isset($this->parentColumn) ? $this->parentColumn : 'parent_id');
    }

    public function allParents(): Relation
    {
        return $this->parent()->with('allParents');
    }

    public function allChildren(): Relation
    {
        return $this->children()->with('allChildren');
    }

    public function getAncestorsAttribute(): Collection
    {
        $curr = $this->allParents;
        $ancestors = collect();

        while ($curr) {
            $ancestors->push($curr);
            $curr = $curr->allParents;
        }

        return $ancestors;
    }

    public function getDescendantsAttribute(): Collection
    {
        $children = $this->allChildren;
        $descendants = collect();

        while (!$children->isEmpty()) {
            $child = $children->shift();

            $descendants->push($child);
            $children = $children->concat($child->children);
        }

        return $descendants;
    }
}
