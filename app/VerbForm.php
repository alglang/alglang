<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;

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
}
