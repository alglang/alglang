<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Builder;

class NominalForm extends Form
{
    public $table = 'forms';

    public function getMorphClass()
    {
        return Form::class;
    }

    public static function booted()
    {
        static::addGlobalScope(function (Builder $query) {
            $query->where('structure_type', NominalStructure::class);
        });
    }

    public function getUrlAttribute(): string
    {
        return "/languages/{$this->language->slug}/nominal-forms/{$this->slug}";
    }

    public function scopeOrderByFeatures(Builder $query): Builder
    {
        $query->join('nominal_structures', 'nominal_structures.id', '=', 'forms.structure_id');
        $query->orderByFeature('pronominal_feature_id', 'pronominal_features');
        $query->orderByFeature('nominal_feature_id', 'nominal_features');

        return $query->select('forms.*');
    }
}