<?php

namespace App\Models;

use App\Traits\Sourceable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class FormGap extends Model
{
    use HasFactory;
    use Sourceable;

    protected $guarded = [];

    public function getUrlAttribute(): string
    {
        $type = $this->structure_type === VerbStructure::class ? 'verb-forms' : 'nominal-forms';

        return "/languages/{$this->language_code}/{$type}/gaps/{$this->id}";
    }

    public function structure(): Relation
    {
        return $this->morphTo('structure');
    }

    public function language(): Relation
    {
        return $this->belongsTo(Language::class);
    }
}
