<?php

namespace App\Models;

use App\Presenters\PhonemePresenter;
use App\Traits\HasReflexes;
use App\Traits\Sourceable;
use App\Traits\Reconstructable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class Phoneme extends Model
{
    use HasFactory;
    use HasReflexes;
    use PhonemePresenter;
    use Reconstructable;
    use Sourceable;

    protected $guarded = [];

    public function getIpaAttribute(): string
    {
        $featureSet = $this->features;

        if ($featureSet instanceof ConsonantFeatureSet
                || $featureSet instanceof VowelFeatureSet
                || $featureSet instanceof ClusterFeatureSet) {
            return $featureSet->shape;
        }

        return '';
    }

    public function getSlugAttribute(?string $slug): string
    {
        if ($slug) {
            return $slug;
        }
        return $this->shape ?? $this->ipa;
    }

    public function getUrlAttribute(): string
    {
        if (!$this->featureable_type) {
            return '';
        }

        return "/languages/{$this->language_code}/{$this->type}s/{$this->slug}";
    }

    public function getTypeAttribute(): string
    {
        switch ($this->featureable_type) {
            case VowelFeatureSet::class:
                return 'vowel';
            case ConsonantFeatureSet::class:
                return 'consonant';
            case ClusterFeatureSet::class:
                return 'cluster';
            default:
                return '';
        };
    }

    public function language(): Relation
    {
        return $this->belongsTo(Language::class, 'language_code');
    }

    public function features(): Relation
    {
        return $this->morphTo('features', 'featureable_type', 'featureable_id');
    }

    public function allophones(): Relation
    {
        return $this->hasMany(Allophone::class);
    }

    protected static function booted()
    {
        static::saving(function (self $phoneme) {
            $phoneme->slug = $phoneme->slug;
        });
    }
}
