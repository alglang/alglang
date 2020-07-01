<?php

namespace App;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Morpheme extends Model
{
    use HasSlug;

    protected $with = ['language'];

    protected $appends = ['url'];

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

    protected function generateNonUniqueSlug(): string
    {
        $slugField = $this->slugOptions->slugField;

        if ($this->hasCustomSlugBeenUsed() && ! empty($this->$slugField)) {
            return $this->$slugField;
        }

        $sourceString = mb_ereg_replace('·', '0', $this->getSlugSourceString());

        return Str::slug($sourceString, $this->slugOptions->slugSeparator, $this->slugOptions->slugLanguage);
    }
}
