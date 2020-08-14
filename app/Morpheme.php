<?php

namespace App;

use Adoxography\Disambiguatable\Disambiguatable;
use App\Traits\Sourceable;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Morpheme extends Model
{
    use Disambiguatable;
    use HasSlug;
    use Sourceable;

    protected $guarded = [];

    protected $with = ['language'];

    protected $appends = ['url'];

    /**
     * @var int
     */
    public $verb_forms_count;

    /**
     * @var int
     */
    public $nominal_forms_count;

    /**
     * @var Collection
     */
    protected $glosses_;

    /**
     * @var Collection
     */
    protected $forms_;

    /**
     * @var Collection
     */
    protected $verbForms_;

    /**
     * @var Collection
     */
    protected $nominalForms_;

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

    public function getFormsAttribute(): Collection
    {
        if (isset($this->forms_)) {
            return $this->forms_;
        }

        $this->forms_ = $this->forms()->get();

        return $this->forms_;
    }

    public function getVerbFormsAttribute(): Collection
    {
        if (isset($this->verbForms_)) {
            return $this->verbForms_;
        }

        $this->verbForms_ = $this->verbForms()->get();

        return $this->verbForms_;
    }

    public function getNominalFormsAttribute(): Collection
    {
        if (isset($this->nominalForms_)) {
            return $this->nominalForms_;
        }

        $this->nominalForms_ = $this->nominalForms()->get();

        return $this->nominalForms_;
    }

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    |
    */

    public function forms(): Builder
    {
        return Form::where('language_id', $this->language_id)
            ->where(function (Builder $query) {
                $query->where('morpheme_structure', "$this->id")
                      ->orWhere('morpheme_structure', 'LIKE', "{$this->id}-%")
                      ->orWhere('morpheme_structure', 'LIKE', "%-{$this->id}")
                      ->orWhere('morpheme_structure', 'LIKE', "%-{$this->id}-%");
            });
    }

    public function verbForms(): Builder
    {
        return $this->forms()->where('structure_type', VerbStructure::class);
    }

    public function nominalForms(): Builder
    {
        return $this->forms()->where('structure_type', NominalStructure::class);
    }

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
}
