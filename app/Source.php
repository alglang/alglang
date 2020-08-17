<?php

namespace App;

use Adoxography\Disambiguatable\Disambiguatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class Source extends Model
{
    use Disambiguatable;

    /** @var array */
    protected $disambiguatableFields = ['author', 'year'];

    public static function booted()
    {
        self::created(function (self $model) {
            $tokens = [
                ...explode(' ', $model->author),
                $model->year
            ];

            $slug = strtolower(implode('-', $tokens));

            $model->slug = $slug;
            $model->save();
        });

        static::addGlobalScope('order', function (Builder $query) {
            $query->orderBy('author')->orderBy('year', 'desc');
        });
    }

    public function getUrlAttribute(): string
    {
        return "/sources/{$this->slug}";
    }

    public function getShortCitationAttribute(): string
    {
        $citation = "$this->author $this->year";

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

    public function afterDisambiguated(): void
    {
        $tokens = [
            ...explode(' ', $this->author),
            $this->year,
            $this->disambiguation_letter
        ];

        $slug = strtolower(implode('-', $tokens));
        $this->slug = $slug;
        $this->save();
    }
}
