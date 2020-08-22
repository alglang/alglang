<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class VerbForm extends Form
{
    public $table = 'forms';

    public static function booted()
    {
        static::addGlobalScope('verbForm', function (Builder $query) {
            $query->where('structure_type', VerbStructure::class);
        });
    }

    public function getMorphClass()
    {
        return Form::class;
    }

    public function getUrlAttribute(): string
    {
        return "/languages/{$this->language->slug}/verb-forms/{$this->slug}";
    }

    public function getParadigmAttribute(): VerbParadigm
    {
        return VerbParadigm::generate($this->language, $this->structure);
    }

    public function structure(): Relation
    {
        return $this->belongsTo(VerbStructure::class);
    }

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
}
