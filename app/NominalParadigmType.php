<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NominalParadigmType extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Configuration
    |--------------------------------------------------------------------------
    |
    */

    public $incrementing = false;

    public $timestamps = false;

    protected $primaryKey = 'name';

    protected $keyType = 'str';

    protected $guarded = [];

    /*
    |--------------------------------------------------------------------------
    | Attribute accessors
    |--------------------------------------------------------------------------
    |
    */

    public function getMetaTypeAttribute(): string
    {
        if ($this->has_pronominal_feature && $this->has_nominal_feature) {
            return 'Possessed noun';
        }

        if ($this->has_pronominal_feature) {
            return 'Personal pronoun';
        }

        if ($this->has_nominal_feature) {
            return 'Third person form';
        }

        throw new \UnexpectedValueException('Paradigm has no features; cannot extrapolate a meta type');
    }
}
