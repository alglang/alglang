<?php

namespace App;

use App\Traits\HasParent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

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

    /** @var string */
    public $parentColumn = 'parent_name';

    public $incrementing = false;

    protected $primaryKey = 'name';

    protected $keyType = 'str';

    protected $guarded = [];

    protected $appends = ['url'];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
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
    | Methods
    |--------------------------------------------------------------------------
    |
    */

    public function languagesWithDescendants(): Collection
    {
        /** @var Collection */
        $languages = $this->languages;

        foreach ($languages as $language) {
            $languages = $languages->concat($language->descendants);
        }

        return $languages;
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

    /*
    |--------------------------------------------------------------------------
    | Protected methods
    |--------------------------------------------------------------------------
    |
    */

    protected static function booted(): void
    {
        static::addGlobalScope('order', function (Builder $query) {
            $query->orderBy('order_key');
        });
    }
}
