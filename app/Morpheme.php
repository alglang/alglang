<?php

namespace App;

use Adoxography\Disambiguatable\Disambiguatable;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Morpheme extends Model
{
    use Disambiguatable;
    use HasSlug;

    protected $guarded = [];

    protected $with = ['language'];

    protected $appends = ['url'];

    /**
     * @var Collection
     */
    protected $glosses_;

    /**
     * @var bool
     */
    protected $alwaysDisambiguate = true;

    /**
     * @var array
     */
    protected $disambiguatableFields = ['language_id', 'shape'];

    protected static function booted()
    {
        static::saved(function (Morpheme $model) {
            $model->updateSlugBasedOnDisambiguator();
        });

        static::deleted(function (Morpheme $model) {
            $model->disambiguatableDuplicates()
                  ->each(function (Morpheme $morpheme) {
                      $morpheme->updateSlugBasedOnDisambiguator();
                  });
        });
    }

    protected function updateSlugBasedOnDisambiguator(): void
    {
        $pieces = collect(explode('-', $this->slug));
        $disambiguator = $this->disambiguator + 1;

        if ($pieces->last() !== strval($disambiguator)) {
            if (is_numeric($pieces->last())) {
                $pieces->pop();
            }

            $pieces->push($disambiguator);
            $this->slug = $pieces->join('-');
            $this->save();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Attribute accessors
    |--------------------------------------------------------------------------
    |
    */

    public function getUrlAttribute(): string
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

    public function getGlossesAttribute(): Collection
    {
        if (isset($this->glosses_)) {
            return $this->glosses_;
        }

        $abvs = collect(explode('.', $this->gloss));
        $existing = Gloss::find($abvs);

        $this->glosses_ = $abvs->map(function ($abv) use ($existing) {
            return $existing->firstWhere('abv', $abv) ?? new Gloss(['abv' => $abv]);
        });

        return $this->glosses_;
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

    public function slot(): Relation
    {
        return $this->belongsTo(Slot::class, 'slot_abv', 'abv');
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

        if ($sourceString === false) {
            $sourceString = '';
        }

        return Str::slug($sourceString, $this->slugOptions->slugSeparator, $this->slugOptions->slugLanguage);
    }
}
