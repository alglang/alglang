<?php

namespace App;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasSlug;

    protected $guarded = [];

    public function getUrlAttribute()
    {
        return route('groups.show', ['group' => $this], false);
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function languages()
    {
        return $this->hasMany(Language::class);
    }
}
