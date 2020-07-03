<?php

namespace App;

use Adoxography\Disambiguatable\Disambiguatable;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Morpheme extends Model
{
    use Disambiguatable;
    use HasSlug;

    protected $with = ['language'];

    protected $appends = ['url'];

    protected $glosses_;

    protected $alwaysDisambiguate = true;
    protected $disambiguatableFields = ['language_id', 'shape'];

    protected static function booted()
    {
        static::saved(function (Model $model) {
            $model->updateSlugBasedOnDisambiguator();
        });

        static::deleted(function (Model $model) {
            $model->_disambiguatableDuplicates()
                  ->each(function (Morpheme $morpheme) {
                      $morpheme->updateSlugBasedOnDisambiguator();
                  });
        });
    }

    protected function updateSlugBasedOnDisambiguator()
    {
        $pieces = explode('-', $this->slug);
        $changed = false;
        $disambiguator = $this->disambiguator + 1;

        if (count($pieces) <= 1 || !is_numeric($pieces[count($pieces) - 1])) {
            $pieces[] = $disambiguator;
            $changed = true;
        } elseif ((int)$pieces[count($pieces) - 1] !== $disambiguator) {
            $pieces[count($pieces) - 1] = $disambiguator;
            $changed = true;
        }

        if ($changed) {
            $this->slug = implode('-', $pieces);
            $this->save();
        }
    }

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

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function slot()
    {
        return $this->belongsTo(Slot::class, 'slot_abv', 'abv');
    }

    public function getGlossesAttribute()
    {
        if (!isset($this->glosses_)) {
            $abvs = explode('.', $this->gloss);
            $glossesFromDatabase = Gloss::findOrNew($abvs);
            $glosses = array_map(function ($abv) use ($glossesFromDatabase) {
                $gloss = $glossesFromDatabase->firstWhere('abv', $abv);
                return $gloss ?? new Gloss(['abv' => $abv]);
            }, $abvs);

            $this->glosses_ = collect($glosses);
        }

        return $this->glosses_;
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
