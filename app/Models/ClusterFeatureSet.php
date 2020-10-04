<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class ClusterFeatureSet extends Model
{
    use HasFactory;

    protected $with = ['firstSegment', 'secondSegment'];

    public function firstSegment(): Relation
    {
        return $this->belongsTo(Phoneme::class);
    }

    public function secondSegment(): Relation
    {
        return $this->belongsTo(Phoneme::class);
    }
}
