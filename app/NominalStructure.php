<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class NominalStructure extends Model
{
    public function pronominalFeature(): Relation
    {
        return $this->belongsTo(NominalFeature::class);
    }

    public function nominalFeature(): Relation
    {
        return $this->belongsTo(NominalFeature::class);
    }

    public function paradigm(): Relation
    {
        return $this->belongsTo(NominalParadigm::class);
    }
}
