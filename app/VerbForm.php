<?php

namespace App;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class VerbForm extends Model
{
    use HasSlug;

    protected $guarded = [];

    protected $with = ['language'];

    /*
    |--------------------------------------------------------------------------
    | Attribute accessors
    |--------------------------------------------------------------------------
    |
    */

    public function getUrlAttribute(): string
    {
        return route(
            'verb-forms.show',
            [
                'language' => $this->language,
                'verbForm' => $this
            ],
            false
        );
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
