<?php

namespace App\Models;

use App\Presenters\ReflexPresenter;
use App\Traits\Sourceable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class Reflex extends Model
{
    use HasFactory;
    use Sourceable;
    use ReflexPresenter;

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
