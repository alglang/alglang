<?php

namespace App;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Builder as Builder;

class Language extends Model
{
    use HasSlug;

    protected $guarded = [];

    protected $appends = ['url'];

    protected $casts = [
        'reconstructed' => 'bool'
    ];

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

    public function parent(): Relation
    {
        return $this->belongsTo(Language::class);
    }

    public function group(): Relation
    {
        return $this->belongsTo(Group::class);
    }

    public function children(): Relation
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function morphemes(): Relation
    {
        return $this->hasMany(Morpheme::class);
    }

    public function verbForms(): Relation
    {
        return $this->hasMany(VerbForm::class);
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
            ->generateSlugsFrom('algo_code')
            ->saveSlugsTo('slug');
    }
}
