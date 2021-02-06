<?php

namespace App\Models;

use App\Contracts\HasMorphemes;
use App\Contracts\HasPhonemes;
use App\Contracts\HasSources;
use App\Contracts\HasVerbForms;
use App\Contracts\HasNominalForms;
use App\Traits\AggregatesSources;
use App\Traits\HasParent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Language extends Model implements HasMorphemes, HasSources, HasVerbForms, HasNominalForms, HasPhonemes
{
    use AggregatesSources;
    use HasFactory;
    use HasParent;

    /*
    |--------------------------------------------------------------------------
    | Configuration
    |--------------------------------------------------------------------------
    |
    */

    public $incrementing = false;

    protected $primaryKey = 'code';

    protected $keyType = 'str';

    protected $guarded = [];

    protected $appends = ['url'];

    protected $casts = [
        'reconstructed' => 'bool',
        'alternate_names' => 'array',
        'position' => 'array'
    ];

    /** @var array */
    protected $sourcedRelations = [
        'morphemes',
        'forms',
        'nominalParadigms'
    ];

    /** @var string */
    protected $parentColumn = 'parent_code';

    /*
    |--------------------------------------------------------------------------
    | Attribute accessors
    |--------------------------------------------------------------------------
    |
    */

    public function getSlugAttribute(): string
    {
        return $this->code;
    }

    public function getUrlAttribute(): string
    {
        return route('languages.show', ['language' => $this], false);
    }

    public function getVStemAttribute(): Morpheme
    {
        return $this->morphemes()->where('shape', 'V-')->first();
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

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function morphemes(): HasMany
    {
        return $this->hasMany(Morpheme::class);
    }

    public function forms(): HasMany
    {
        return $this->hasMany(Form::class);
    }

    public function verbForms(): HasMany
    {
        return $this->hasMany(VerbForm::class);
    }

    public function nominalForms(): HasMany
    {
        return $this->hasMany(NominalForm::class);
    }

    public function gaps(): HasMany
    {
        return $this->hasMany(FormGap::class);
    }

    public function verbGaps(): HasMany
    {
        return $this->hasMany(VerbGap::class);
    }

    public function nominalGaps(): HasMany
    {
        return $this->hasMany(NominalGap::class);
    }

    public function nominalParadigms(): HasMany
    {
        return $this->hasMany(NominalParadigm::class);
    }

    public function rules(): HasMany
    {
        return $this->hasMany(Rule::class);
    }

    public function phonoids(): HasMany
    {
        return $this->hasMany(Phoneme::class);
    }

    public function phonemes(): HasMany
    {
        return $this->phonoids()->whereIn('featureable_type', [VowelFeatureSet::class, ConsonantFeatureSet::class]);
    }

    public function vowels(): HasMany
    {
        return $this->phonoids()->where('featureable_type', VowelFeatureSet::class);
    }

    public function consonants(): HasMany
    {
        return $this->phonoids()->where('featureable_type', ConsonantFeatureSet::class);
    }

    public function clusters(): HasMany
    {
        return $this->phonoids()->where('featureable_type', ClusterFeatureSet::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Protected methods
    |--------------------------------------------------------------------------
    |
    */

    protected static function booted()
    {
        static::created(function (Language $language) {
            $stem = Slot::firstOrCreate([
                'abv' => 'STM',
                'name' => 'stem'
            ]);

            $language->morphemes()->create([
                'shape' => 'V-',
                'slot_abv' => $stem->abv,
                'gloss' => 'V'
            ]);

            $language->morphemes()->create([
                'shape' => 'N-',
                'slot_abv' => $stem->abv,
                'gloss' => 'N'
            ]);
        });

        static::addGlobalScope('order', function (Builder $query) {
            $query->orderBy('order_key');
        });
    }
}
