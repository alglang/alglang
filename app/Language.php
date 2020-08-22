<?php

namespace App;

use App\Traits\HasParent;
use Astrotomic\CachableAttributes\CachableAttributes;
use Astrotomic\CachableAttributes\CachesAttributes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Builder as Builder;
use Illuminate\Database\Eloquent\Collection;

class Language extends Model implements CachableAttributes
{
    use CachesAttributes;
    use HasParent;
    use HasSlug;

    /** @var int */
    public $sources_count;

    /*
    |--------------------------------------------------------------------------
    | Configuration
    |--------------------------------------------------------------------------
    |
    */

    protected $guarded = [];

    protected $appends = ['url'];

    protected $casts = [
        'reconstructed' => 'bool'
    ];

    /** @var array */
    protected $cachableAttributes = [
        'sources'
    ];

    /** @var array */
    protected $sourcedRelations = [
        'morphemes',
        'forms',
        'nominalParadigms'
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('algo_code')
            ->saveSlugsTo('slug');
    }

    /*
    |--------------------------------------------------------------------------
    | Hooks
    |--------------------------------------------------------------------------
    |
    */

    public static function booted()
    {
        static::created(function (Language $language) {
            $language->morphemes()->create([
                'shape' => 'V-',
                'slot_abv' => 'STM',
                'gloss' => 'V'
            ]);

            $language->morphemes()->create([
                'shape' => 'N-',
                'slot_abv' => 'STM',
                'gloss' => 'N'
            ]);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Attribute accessors
    |--------------------------------------------------------------------------
    |
    */

    public function getPositionAttribute(?string $value): ?object
    {
        return json_decode($value);
    }

    public function getUrlAttribute(): string
    {
        return route('languages.show', ['language' => $this], false);
    }

    public function getSourcesAttribute(): Collection
    {
        return $this->remember('sources', 0, function () {
            return $this->sources()->get();
        });
    }

    public function getVStemAttribute(): Morpheme
    {
        return $this->morphemes()->where('shape', 'V-')->first();
    }

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    |
    */

    public function loadSourcesCount(): void
    {
        $this->sources_count = $this->sources()->count();
    }

    /*
    |--------------------------------------------------------------------------
    | Query scopes
    |--------------------------------------------------------------------------
    |
    */

    public static function scopePositioned(Builder $query): Builder
    {
        return $query->whereNotNull('position');
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    |
    */

    public function group(): Relation
    {
        return $this->belongsTo(Group::class);
    }

    public function morphemes(): Relation
    {
        return $this->hasMany(Morpheme::class);
    }

    public function forms(): Relation
    {
        return $this->hasMany(Form::class);
    }

    public function verbForms(): Relation
    {
        return $this->hasMany(VerbForm::class);
    }

    public function nominalForms(): Relation
    {
        return $this->hasMany(NominalForm::class);
    }

    public function nominalParadigms(): Relation
    {
        return $this->hasMany(NominalParadigm::class);
    }

    public function sources(): Builder
    {
        $query = Source::query();

        foreach ($this->sourcedRelations as $relation) {
            $query = $query->orWhereHas($relation, function ($query) {
                return $query->where('language_id', $this->id);
            });
        }

        return $query;
    }
}
