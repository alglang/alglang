<?php

namespace App\Models;

use App\Contracts\HasMorphemes;
use App\Contracts\HasSources;
use App\Contracts\HasVerbForms;
use App\Contracts\HasNominalForms;
use App\Traits\AggregatesSources;
use App\Traits\HasParent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Relation;

class Language extends Model implements HasMorphemes, HasSources, HasVerbForms, HasNominalForms
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

    public function group(): Relation
    {
        return $this->belongsTo(Group::class);
    }

    public function morphemes(): HasMany
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

    public function gaps(): Relation
    {
        return $this->hasMany(FormGap::class);
    }

    public function verbGaps(): Relation
    {
        return $this->hasMany(VerbGap::class);
    }

    public function nominalGaps(): Relation
    {
        return $this->hasMany(NominalGap::class);
    }

    public function nominalParadigms(): Relation
    {
        return $this->hasMany(NominalParadigm::class);
    }

    public function rules(): Relation
    {
        return $this->hasMany(Rule::class);
    }

    public function phonemes(): Relation
    {
        return $this->hasMany(Phoneme::class);
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
