<?php

namespace App\Models;

use App\Contracts\HasExamples;
use App\Presenters\FormPresenter;
use App\Traits\HasParent;
use App\Traits\Reconstructable;
use App\Traits\Sourceable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Form extends Model implements HasExamples
{
    use FormPresenter;
    use HasFactory;
    use HasParent;
    use HasSlug;
    use Reconstructable;
    use Sourceable;

    /*
    |--------------------------------------------------------------------------
    | Configuration
    |--------------------------------------------------------------------------
    |
    */

    protected $guarded = [];

    protected $with = ['language'];

    protected $appends = ['morphemes', 'url'];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('shape')
            ->saveSlugsTo('slug');
    }

    /*
    |--------------------------------------------------------------------------
    | Hooks
    |--------------------------------------------------------------------------
    |
    */

    public static function boot()
    {
        parent::boot();

        self::saving(function (self $model) {
            $model->slug = $model->shape;
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Attribute accessors
    |--------------------------------------------------------------------------
    |
    */

    public function getUrlAttribute(): string
    {
        switch ($this->structure_type) {
            case VerbStructure::class:
                return (new VerbForm($this->attributes))->url;
            case NominalStructure::class:
                return (new NominalForm($this->attributes))->url;
            default:
                throw new \UnexpectedValueException("Unknown verb form type '{$this->structure_type}'");
        }
    }

    public function getMorphemesAttribute(): Collection
    {
        $morphemes = $this->morphemeConnections->pluck('morpheme');

        foreach ($morphemes as $morpheme) {
            $morpheme->language_code = $morpheme->language_code ?? $this->language_code;
        }

        return $morphemes;
    }

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    |
    */

    public function assignMorphemes(iterable $morphemes): void
    {
        $this->morphemeConnections()->delete();

        foreach ($morphemes as $position => $morpheme) {
            $this->morphemeConnections()->create([
                'position' => $position,
                'shape' => is_string($morpheme) ? trim($morpheme, '-') : null,
                'morpheme_id' => is_string($morpheme) ? null : $morpheme->id
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Query scopes
    |--------------------------------------------------------------------------
    |
    */

    public function scopeOrderByFeature(Builder $query, string $column, string $table): Builder
    {
        $query->leftJoin("features as {$table}", "{$table}.name", '=', $column);

        $query->orderByRaw(<<<SQL
            CASE
                WHEN {$table}.person in ('1', '2', '21') THEN {$table}.number
                WHEN {$table}.person = '3' THEN 10
                WHEN {$table}.person = '0' THEN 11
                ELSE 12
            END,
            CASE {$table}.person
                WHEN '1' THEN 10
                WHEN '21' THEN 11
                WHEN '2' THEN 12
                ELSE {$table}.number
            END,
            {$table}.obviative_code
        SQL);

        return $query;
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    |
    */

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public function examples(): HasMany
    {
        return $this->hasMany(Example::class, 'form_id');
    }

    public function morphemeConnections(): HasMany
    {
        return $this->hasMany(MorphemeConnection::class, 'form_id');
    }
}
