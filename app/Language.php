<?php

namespace App;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasSlug;

    protected $guarded = [];

    public function getPositionAttribute($value)
    {
        return json_decode($value);
    }

    public function getUrlAttribute()
    {
        return route('languages.show', ['language' => $this], false);
    }

    public function getMapDataAttribute()
    {
        return [
            'content' => "<a href=\"$this->url\">$this->name</a>",
            'position' => $this->position
        ];
    }

    public static function scopePositioned($query)
    {
        return $query->whereNotNull('position');
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function morphemes()
    {
        return $this->hasMany(Morpheme::class);
    }

    public function verbForms()
    {
        return $this->hasMany(VerbForm::class);
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('algo_code')
            ->saveSlugsTo('slug');
    }
}
