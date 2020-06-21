<?php

namespace App;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasSlug;

    protected $guarded = [];

    public function getUrlAttribute()
    {
        return route('languages.show', ['language' => $this], false);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function morphemes()
    {
        return $this->hasMany(Morpheme::class);
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('algo_code')
            ->saveSlugsTo('slug');
    }
}
