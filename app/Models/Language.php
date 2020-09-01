<?php

namespace App\Models;

use App\Traits\AggregatesSources;
use App\Traits\HasParent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class Language extends Model
{
    use AggregatesSources;
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

    /*
    |--------------------------------------------------------------------------
    | Protected methods
    |--------------------------------------------------------------------------
    |
    */

    protected static function booted()
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

        static::addGlobalScope('order', function (Builder $query) {
            $query->orderBy('order_key');
        });
    }
}
