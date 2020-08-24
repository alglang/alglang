<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\Relation;

trait HasParent
{
    public function parent(): Relation
    {
        return $this->belongsTo(self::class);
    }

    public function children(): Relation
    {
        return $this->hasMany(self::class, $this->parentColumn ?? 'parent_id');
    }
}
