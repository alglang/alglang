<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class VerbForm extends Form
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
        static::addGlobalScope('verbForm', function (Builder $query) {
            $query->where('structure_type', VerbStructure::class);
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
        return route('verbForms.show', [
            'language' => $this->language->slug,
            'verbForm' => $this->slug
        ], false);
    }

    public function getParadigmAttribute(): VerbParadigm
    {
        return VerbParadigm::generate($this->language, $this->structure);
    }

    /*
    |--------------------------------------------------------------------------
    | Query scopes
    |--------------------------------------------------------------------------
    |
    */

    public function scopeOrderByFeatures(Builder $query): Builder
    {
        if (!queryHasJoin($query, 'verb_structures')) {
            $query->join('verb_structures', 'verb_structures.id', '=', 'forms.structure_id');
        }

        $query->orderByFeature('subject_name', 'subject_features');
        $query->orderByFeature('primary_object_name', 'primary_object_features');
        $query->orderByFeature('secondary_object_name', 'secondary_object_features');

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
        return $this->belongsTo(VerbStructure::class);
    }
}
