<?php

namespace App\Models;

use App\Contracts\HasMorphemes;
use App\Contracts\HasNominalForms;
use App\Contracts\HasVerbForms;
use Adoxography\Disambiguatable\Disambiguatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;

class Source extends Model implements HasMorphemes, HasVerbForms, HasNominalForms
{
    use Disambiguatable;
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Configuration
    |--------------------------------------------------------------------------
    |
    */

    protected $guarded = [];

    /** @var array */
    protected $disambiguatableFields = ['author', 'year'];

    /*
    |--------------------------------------------------------------------------
    | Attribute accessors
    |--------------------------------------------------------------------------
    |
    */

    public function getUrlAttribute(): string
    {
        if (!$this->slug) {
            return '';
        }

        return route('sources.show', [
            'source' => $this->slug
        ], false);
    }

    public function getShortCitationAttribute(): string
    {
        $citation = "{$this->author} {$this->year}";

        if ($this->disambiguation_letter) {
            $citation .= $this->disambiguation_letter;
        }

        return $citation;
    }

    public function getDisambiguationLetterAttribute(): ?string
    {
        if (!$this->disambiguationIsSet()) {
            return null;
        }

        return chr($this->disambiguator + ord('a'));
    }

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    |
    */

    public function afterDisambiguated(): void
    {
        $this->slug = $this->generateSlug();
        $this->save();
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    |
    */

    public function morphemes(): Relation
    {
        return $this->morphedByMany(Morpheme::class, 'sourceable');
    }

    public function forms(): Relation
    {
        return $this->morphedByMany(Form::class, 'sourceable');
    }

    public function verbForms(): Relation
    {
        return $this->morphedByMany(VerbForm::class, 'sourceable');
    }

    public function nominalForms(): Relation
    {
        return $this->morphedByMany(NominalForm::class, 'sourceable');
    }

    public function nominalParadigms(): Relation
    {
        return $this->morphedByMany(NominalParadigm::class, 'sourceable');
    }

    public function examples(): Relation
    {
        return $this->morphedByMany(Example::class, 'sourceable');
    }

    /*
    |--------------------------------------------------------------------------
    | Protected methods
    |--------------------------------------------------------------------------
    |
    */

    protected static function booted()
    {
        self::created(function (self $model) {
            $model->slug = $model->generateSlug();
            $model->save();
        });

        static::addGlobalScope('order', function (Builder $query) {
            $query->orderBy('author')->orderBy('year', 'desc');
        });
    }

    protected function generateSlug(): string
    {
        return Str::slug("{$this->author} {$this->year} {$this->disambiguation_letter}");
    }
}
