<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class NominalGap extends FormGap
{
    public $table = 'form_gaps';

    public function getMorphClass()
    {
        return FormGap::class;
    }

    public static function booted()
    {
        static::addGlobalScope(function (Builder $query) {
            $query->where('structure_type', NominalStructure::class);
        });
    }
}
