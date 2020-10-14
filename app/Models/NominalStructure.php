<?php

namespace App\Models;

use App\Presenters\NominalStructurePresenter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class NominalStructure extends Model
{
    use HasFactory;
    use NominalStructurePresenter;

    /*
    |--------------------------------------------------------------------------
    | Configuration
    |--------------------------------------------------------------------------
    |
    */

    protected $guarded = [];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    |
    */

    public function pronominalFeature(): Relation
    {
        return $this->belongsTo(Feature::class);
    }

    public function nominalFeature(): Relation
    {
        return $this->belongsTo(Feature::class);
    }

    public function paradigm(): Relation
    {
        return $this->belongsTo(NominalParadigm::class);
    }
}
