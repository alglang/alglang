<?php

namespace App;

use Adoxography\Disambiguatable\Disambiguatable;
use App\Traits\HasParent;
use App\Traits\Sourceable;
use Astrotomic\CachableAttributes\CachableAttributes;
use Astrotomic\CachableAttributes\CachesAttributes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Morpheme extends Model implements CachableAttributes
{
    use CachesAttributes;
    use Disambiguatable;
    use HasParent;
    use HasSlug;
    use Sourceable;

    /**
     * @var int
     */
    public $verb_forms_count;

    /**
     * @var int
     */
    public $nominal_forms_count;

    /*
    |--------------------------------------------------------------------------
    | Configuration
    |--------------------------------------------------------------------------
    |
    */

    protected $guarded = [];

    protected $with = ['language'];

    protected $appends = ['url'];

    /** @var array */
    protected $cachableAttributes = [
        'glosses'
    ];

    /**
     * @var bool
     */
    protected $alwaysDisambiguate = true;

    /**
     * @var array
     */
    protected $disambiguatableFields = ['language_code', 'shape'];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('shape')
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
        return "/languages/{$this->language->slug}/morphemes/{$this->slug}";
    }

    public function getGlossesAttribute(): Collection
    {
        return $this->remember('glosses', 0, function () {
            $abvs = collect(explode('.', $this->gloss));
            $existing = Gloss::find($abvs);

            return $abvs->map(function ($abv) use ($existing) {
                return $existing->firstWhere('abv', $abv) ?? new Gloss(['abv' => $abv]);
            });
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    |
    */

    public function loadVerbFormsCount(): void
    {
        $this->verb_forms_count = $this->verbForms()->count();
    }

    public function loadNominalFormsCount(): void
    {
        $this->nominal_forms_count = $this->nominalForms()->count();
    }

    public function isStem(): bool
    {
        return $this->slot_abv === 'STM';
    }

    public function scopeWithoutPlaceholders(Builder $query): Builder
    {
        return $query->whereNotIn('shape', ['V-', 'N-']);
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    |
    */

    public function language(): Relation
    {
        return $this->belongsTo(Language::class, 'language_code');
    }

    public function slot(): Relation
    {
        return $this->belongsTo(Slot::class, 'slot_abv', 'abv');
    }

    public function forms(): Relation
    {
        return $this->hasManyThrough(
            Form::class,
            MorphemeConnection::class,
            'morpheme_id',
            'id',
            'id',
            'form_id'
        )->where('language_code', $this->language_code)->distinct();
    }

    public function verbForms(): Relation
    {
        return $this->forms()->where('structure_type', VerbStructure::class);
    }

    public function nominalForms(): Relation
    {
        return $this->forms()->where('structure_type', NominalStructure::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Protected methods
    |--------------------------------------------------------------------------
    |
    */

    protected static function booted()
    {
        static::creating(function (Morpheme $model) {
            $model->slug = trim($model->shape, '-');
        });

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
}
