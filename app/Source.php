<?php

namespace App;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;

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

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['author', 'year'])
            ->saveSlugsTo('slug');
    }
}
