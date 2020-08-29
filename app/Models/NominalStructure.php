<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class NominalStructure extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Configuration
    |--------------------------------------------------------------------------
    |
    */

    protected $guarded = [];

    /*
    |--------------------------------------------------------------------------
    | Attribute accessors
    |--------------------------------------------------------------------------
    |
    */

    public function getFeatureStringAttribute(): string
    {
        $features = [];

        if ($this->pronominal_feature_name) {
            $features[] = $this->pronominal_feature_name;
        }

        if ($this->nominal_feature_name) {
            $features[] = $this->nominal_feature_name;
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
