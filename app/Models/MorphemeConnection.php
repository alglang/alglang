<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class MorphemeConnection extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Configuration
    |--------------------------------------------------------------------------
    |
    */

    protected $guarded = [];

    protected $with = ['morpheme'];

    /*
    |--------------------------------------------------------------------------
    | Hooks
    |--------------------------------------------------------------------------
    |
    */

    public static function booted()
    {
        static::addGlobalScope(function (Builder $query) {
            $query->orderBy('position');
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    |
    */

    public function morpheme(): Relation
    {
        return $this->belongsTo(Morpheme::class)->withDefault(function ($morpheme, $connection) {
            $morpheme->shape = $connection->shape;
        });
    }
}
