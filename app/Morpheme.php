<?php

namespace App;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;

class Morpheme extends Model
{
    use HasSlug;

    protected $with = ['language'];

    public function getUrlAttribute()
    {
        return route(
            'morphemes.show',
            [
                'language' => $this->language,
                'morpheme' => $this
            ],
            false
        );
    }

    public function getGlossAttribute()
    {
        return $this->glosses->pluck('abv')->join('.');
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function slot()
    {
        return $this->belongsTo(Slot::class);
    }

    public function glosses()
    {
        return $this->belongsToMany(Gloss::class);
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('shape')
            ->saveSlugsTo('slug');
    }
}
