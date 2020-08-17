<?php

namespace App;

use App\Traits\Sourceable;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class Form extends Model
{
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
