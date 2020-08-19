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

    public function structure(): Relation
    {
        return $this->belongsTo(VerbStructure::class);
    }
}
