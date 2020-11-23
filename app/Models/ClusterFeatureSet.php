<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class ClusterFeatureSet extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $with = ['firstSegment', 'secondSegment'];

    public function getShapeAttribute(): string
    {
        return $this->firstSegment->shape . $this->secondSegment->shape;
    }

    public function firstSegment(): Relation
    {
        return $this->belongsTo(ConsonantFeatureSet::class);
    }

    public function secondSegment(): Relation
    {
        return $this->belongsTo(ConsonantFeatureSet::class);
    }
}
