<?php

namespace App;

use App\Traits\HasParent;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class Group extends Model
{
    use HasParent;
    use HasSlug;

    /*
    |--------------------------------------------------------------------------
    | Configuration
    |--------------------------------------------------------------------------
    |
    */

    protected $primaryKey = 'name';

    protected $keyType = 'str';

    public $incrementing = false;

    protected $guarded = [];

    protected $appends = ['url'];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function getParentColumn(): string
    {
        return 'parent_name';
    }

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

    public function languages(): Relation
    {
        return $this->hasMany(Language::class);
    }
}
