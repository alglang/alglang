<?php

namespace App;

use App\Traits\HasParent;
use App\Traits\Sourceable;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class Form extends Model
{
    use HasParent;
    use HasSlug;
    use Sourceable;

    protected $guarded = [];

    protected $with = ['language'];

    protected $appends = ['morphemes', 'url'];

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
                throw new \Exception('Unknown verb form type');
        };
    }

    public function getMorphemesAttribute(): Collection
    {
        return $this->morphemeConnections->pluck('morpheme');
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

    public function structure(): Relation
    {
        return $this->morphTo();
    }

    public function examples(): Relation
    {
        return $this->hasMany(Example::class, 'form_id');
    }

    public function morphemeConnections(): Relation
    {
        return $this->hasMany(MorphemeConnection::class, 'form_id');
    }

    protected function scopeOrderByFeature(Builder $query, string $column, string $table): Builder
    {
        $query->leftJoin("features as $table", "$table.id", '=', $column);

        $query->orderByRaw(<<<SQL
            CASE
                WHEN $table.person in ('1', '2', '21') THEN $table.number
                WHEN $table.person = '3' THEN 10
                WHEN $table.person = '0' THEN 11
                ELSE 12
            END,
            CASE $table.person
                WHEN '1' THEN 10
                WHEN '21' THEN 11
                WHEN '2' THEN 12
                ELSE $table.number
            END,
            $table.obviative_code
        SQL);

        return $query;
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    |
    */

    public function assignMorphemes(Iterable $morphemes): void
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
