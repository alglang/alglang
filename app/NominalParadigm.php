<?php

namespace App;

use App\Traits\Sourceable;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class NominalParadigm extends Model
{
    use HasSlug;
    use Sourceable;

    public function getUrlAttribute(): string
    {
        return "/languages/{$this->language->slug}/nominal-paradigms/{$this->slug}";
    }

    public function language(): Relation
    {
        return $this->belongsTo(Language::class);
    }

    public function type(): Relation
    {
        return $this->belongsTo(NominalParadigmType::class, 'paradigm_type_id');
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

    /*
    |--------------------------------------------------------------------------
    | HasSlug config
    |--------------------------------------------------------------------------
    |
    */

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }
}
