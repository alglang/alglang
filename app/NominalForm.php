<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Builder;

class NominalForm extends Form
{
    /*
    |--------------------------------------------------------------------------
    | Configuration
    |--------------------------------------------------------------------------
    |
    */

    public $table = 'forms';

    public function getMorphClass()
    {
        return Form::class;
    }

    /*
    |--------------------------------------------------------------------------
    | Hooks
    |--------------------------------------------------------------------------
    |
    */

    public static function booted()
    {
        static::addGlobalScope(function (Builder $query) {
            $query->where('structure_type', NominalStructure::class);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Attribute accessors
    |--------------------------------------------------------------------------
    |
    */

    public function getUrlAttribute(): string
    {
        return "/languages/{$this->language->slug}/nominal-forms/{$this->slug}";
    }

    /*
    |--------------------------------------------------------------------------
    | Query scopes
    |--------------------------------------------------------------------------
    |
    */

    public function scopeOrderByFeatures(Builder $query): Builder
    {
        if (!queryHasJoin($query, 'nominal_structures')) {
            $query->join('nominal_structures', 'nominal_structures.id', '=', 'forms.structure_id');
        }

        $query->orderByFeature('pronominal_feature_name', 'pronominal_features');
        $query->orderByFeature('nominal_feature_name', 'nominal_features');

        return $query->select('forms.*');
    }
}
