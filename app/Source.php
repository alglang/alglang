<?php

namespace App;

use App\Morpheme;
use App\VerbForm;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class Source extends Model
{
    use HasSlug;

    public function getUrlAttribute(): string
    {
        return "/sources/{$this->slug}";
    }

    public function getShortCitationAttribute(): string
    {
        return "$this->author $this->year";
    }

    public function morphemes(): Relation
    {
        return $this->morphedByMany(Morpheme::class, 'sourceable');
    }

    public function verbForms(): Relation
    {
        return $this->morphedByMany(VerbForm::class, 'sourceable');
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['author', 'year'])
            ->saveSlugsTo('slug');
    }
}
