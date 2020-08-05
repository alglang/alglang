<?php

namespace App;

use App\Traits\Sourceable;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class VerbForm extends Model
{
    use HasSlug;
    use Sourceable;

    /** @var Collection */
    private $_morphemes;

    protected $guarded = [];

    protected $with = ['language'];

    protected $appends = ['morphemes'];

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
        return "/languages/{$this->language->slug}/verb-forms/{$this->slug}";
    }

    public function getArgumentStringAttribute(): string
    {
        $string = $this->subject->name;

        if ($this->primaryObject) {
            $string .= "→{$this->primaryObject->name}";
        }

        if ($this->secondaryObject) {
            $string .= "+{$this->secondaryObject->name}";
        }

        return $string;
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

    public function subject(): Relation
    {
        return $this->belongsTo(VerbFeature::class);
    }

    public function primaryObject(): Relation
    {
        return $this->belongsTo(VerbFeature::class);
    }

    public function secondaryObject(): Relation
    {
        return $this->belongsTo(VerbFeature::class);
    }

    public function class(): Relation
    {
        return $this->belongsTo(VerbClass::class);
    }

    public function order(): Relation
    {
        return $this->belongsTo(VerbOrder::class);
    }

    public function mode(): Relation
    {
        return $this->belongsTo(VerbMode::class);
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
