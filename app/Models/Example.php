<?php

namespace App\Models;

use App\Presenters\ExamplePresenter;
use App\Traits\HasParent;
use App\Traits\Reconstructable;
use App\Traits\Sourceable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Example extends Model
{
    use ExamplePresenter;
    use HasParent;
    use HasSlug;
    use Reconstructable;
    use Sourceable;

    /*
    |--------------------------------------------------------------------------
    | Configuration
    |--------------------------------------------------------------------------
    |
    */

    protected $guarded = [];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->saveSlugsTo('slug');
    }

    /*
    |--------------------------------------------------------------------------
    | Hooks
    |--------------------------------------------------------------------------
    |
    */

    public static function booted()
    {
        static::creating(function (self $model) {
            $model->slug = $model->shape;
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Attribute accessors
    |--------------------------------------------------------------------------
    |
    */

    public function getMorphemesAttribute(): Collection
    {
        return $this->form->morphemes->map(function ($morpheme) {
            return $morpheme->isStem() ? $this->stem : $morpheme;
        });
    }

    public function getUrlAttribute(): string
    {
        $routeName = $this->form->structure_type === VerbStructure::class
            ? 'verbForms.examples.show'
            : 'nominalForms.examples.show';

        return route($routeName, [
            'language' => $this->form->language_code,
            'form' => $this->form->slug,
            'example' => $this->slug
        ], false);
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    |
    */

    public function language(): Relation
    {
        return $this->hasOneThrough(
            Language::class,
            Form::class,
            'id',
            'code',
            'form_id',
            'language_code'
        );
    }

    public function form(): Relation
    {
        return $this->belongsTo(Form::class);
    }

    public function stem(): Relation
    {
        return $this->belongsTo(Morpheme::class);
    }
}
