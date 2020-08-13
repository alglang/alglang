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

    /** @var Collection */
    private $_morphemes;

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
        if (isset($this->_morphemes)) {
            return $this->_morphemes;
        }

        $idents = collect(
            $this->morpheme_structure ? explode('-', $this->morpheme_structure) : []
        );

        $morphemePool = Morpheme::find($idents);

        $this->_morphemes = $idents->map(function ($ident) use ($morphemePool) {
            $morpheme = $morphemePool->firstWhere('id', $ident) ?? new Morpheme([
                'shape' => $ident,
                'language_id' => $this->language_id
            ]);

            return $morpheme;
        });

        return $this->_morphemes;
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
