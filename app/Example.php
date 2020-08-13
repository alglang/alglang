<?php

namespace App;

use App\Traits\Sourceable;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Example extends Model
{
    use HasSlug;
    use Sourceable;

    public static function booted()
    {
        static::creating(function (self $model) {
            $model->slug = $model->shape;
        });
    }

    public function getUrlAttribute(): string
    {
        return "/languages/{$this->form->language->slug}/verb-forms/{$this->form->slug}/examples/{$this->slug}";
    }

    public function getMorphemesAttribute(): Collection
    {
        return $this->form->morphemes->map(function ($morpheme) {
            return $morpheme->isStem() ? $this->stem : $morpheme;
        });
    }

    public function language(): Relation
    {
        return $this->hasOneThrough(Language::class, Form::class, 'id', 'id', 'form_id', 'language_id');
    }

    public function form(): Relation
    {
        return $this->belongsTo(Form::class);
    }

    public function stem(): Relation
    {
        return $this->belongsTo(Morpheme::class);
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->saveSlugsTo('slug');
    }
}
