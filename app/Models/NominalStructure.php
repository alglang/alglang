<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class NominalStructure extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Attribute accessors
    |--------------------------------------------------------------------------
    |
    */

    public function getFeatureStringAttribute(): string
    {
        $features = [];

        if ($this->pronominalFeature) {
            $features[] = $this->pronominalFeature->name;
        }

        if ($this->nominalFeature) {
            $features[] = $this->nominalFeature->name;
        }

        return implode('→', $features);
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    |
    */

    public function pronominalFeature(): Relation
    {
        return $this->belongsTo(Feature::class);
    }

    public function nominalFeature(): Relation
    {
        return $this->belongsTo(Feature::class);
    }

    public function paradigm(): Relation
    {
        return $this->belongsTo(NominalParadigm::class);
    }
}
