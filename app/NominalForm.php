<?php

namespace App;

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
}
