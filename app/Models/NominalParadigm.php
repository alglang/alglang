<?php

namespace App\Models;

use App\Traits\Sourceable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class NominalParadigm extends Model
{
    use HasFactory;
    use HasSlug;
    use Sourceable;

    /*
    |--------------------------------------------------------------------------
    | Configuration
    |--------------------------------------------------------------------------
    |
    */

    protected $guarded = [];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    /*
    |--------------------------------------------------------------------------
    | Attribute accessors
    |--------------------------------------------------------------------------
    |
    */

    public function getUrlAttribute(): string
    {
        return route('nominalParadigms.show', [
            'language' => $this->language->slug,
            'nominalParadigm' => $this->slug
        ], false);
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    |
    */

    public function language(): Relation
    {
        return $this->belongsTo(Language::class);
    }

    public function type(): Relation
    {
        return $this->belongsTo(NominalParadigmType::class, 'paradigm_type_name');
    }

    public function forms(): Relation
    {
        return $this->hasManyThrough(
            NominalForm::class,
            NominalStructure::class,
            'paradigm_id',
            'structure_id'
        );
    }
}
