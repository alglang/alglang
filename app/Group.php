<?php

namespace App;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class Group extends Model
{
    use HasSlug;

    protected $guarded = [];

    protected $appends = ['url'];

    /*
    |--------------------------------------------------------------------------
    | Attribute accessors
    |--------------------------------------------------------------------------
    |
    */

    public function getUrlAttribute(): string
    {
        return route('groups.show', ['group' => $this], false);
    }

    public function getPreviewAttribute(): ?string
    {
        return $this->description;
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    |
    */

    public function parent(): Relation
    {
        return $this->belongsTo(self::class);
    }

    public function children(): Relation
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function languages(): Relation
    {
        return $this->hasMany(Language::class);
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
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }
}
