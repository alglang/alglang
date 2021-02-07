<?php

namespace App\Models;

use Adoxography\Disambiguatable\Disambiguatable;
use App\Presenters\MorphemePresenter;
use App\Traits\HasGlosses;
use App\Traits\HasParent;
use App\Traits\Reconstructable;
use App\Traits\Sourceable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Morpheme extends Model
{
    use Disambiguatable;
    use HasFactory;
    use HasGlosses;
    use HasParent;
    use MorphemePresenter;
    use Reconstructable;
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

    /**
     * @var bool
     */
    protected $alwaysDisambiguate = true;

    /**
     * @var array
     */
    protected $disambiguatableFields = ['language_code', 'shape'];

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

    public function isPlaceholder(): bool
    {
        return $this->shape === 'V-' || $this->shape === 'N-';
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

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_code');
    }

    public function slot(): BelongsTo
    {
        return $this->belongsTo(Slot::class, 'slot_abv', 'abv');
    }

    public function forms(): HasManyThrough
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

    public function verbForms(): HasManyThrough
    {
        return $this->forms()->where('structure_type', VerbStructure::class);
    }

    public function nominalForms(): HasManyThrough
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
