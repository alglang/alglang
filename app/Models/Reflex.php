<?php

namespace App\Models;

use App\Presenters\ReflexPresenter;
use App\Traits\Sourceable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Reflex extends Pivot
{
    use HasFactory;
    use Sourceable;
    use ReflexPresenter;

    public $table = 'reflexes';
    protected $guarded = [];

    public function getUrlAttribute(): string
    {
        return "/languages/{$this->phoneme->language_code}/{$this->phoneme->type}s/{$this->phoneme->slug}/reflexes/{$this->reflex->slug}";
    }

    public function phoneme(): Relation
    {
        return $this->belongsTo(Phoneme::class);
    }

    public function reflex(): Relation
    {
        return $this->belongsTo(Phoneme::class);
    }
}
