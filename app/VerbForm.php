<?php

namespace App;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;

class VerbForm extends Model
{
    use HasSlug;

    protected $guarded = [];

    public function getUrlAttribute()
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

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function subject()
    {
        return $this->belongsTo(VerbFeature::class);
    }

    public function class()
    {
        return $this->belongsTo(VerbClass::class);
    }

    public function order()
    {
        return $this->belongsTo(VerbOrder::class);
    }

    public function mode()
    {
        return $this->belongsTo(VerbMode::class);
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('shape')
            ->saveSlugsTo('slug');
    }
}
