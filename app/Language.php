<?php

namespace App;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;

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

    public function getPositionAttribute($value)
    {
        return json_decode($value);
    }

    public function getUrlAttribute()
    {
        return route('languages.show', ['language' => $this], false);
    }

    /*
    |--------------------------------------------------------------------------
    | Query scopes
    |--------------------------------------------------------------------------
    |
    */

    public static function scopePositioned($query)
    {
        return $query->whereNotNull('position');
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    |
    */

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function morphemes()
    {
        return $this->hasMany(Morpheme::class);
    }

    public function verbForms()
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
