<?php

namespace App\Presenters;

trait NominalStructurePresenter
{
    public function getNameAttribute(): string
    {
        return "{$this->feature_string} {$this->paradigm->name}";
    }

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
}
