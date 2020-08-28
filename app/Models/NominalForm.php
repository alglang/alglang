<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

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
        return route('nominalForms.show', [
            'language' => $this->language->slug,
            'nominalForm' => $this->slug
        ], false);
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

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    |
    */

    public function structure(): Relation
    {
        return $this->belongsTo(NominalStructure::class);
    }
}
