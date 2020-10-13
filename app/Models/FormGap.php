<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormGap extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getUrlAttribute(): string
    {
        $type = $this->structure_type === VerbStructure::class ? 'verb-forms' : 'nominal-forms';

        return "/languages/{$this->language_code}/{$type}/gaps/{$this->id}";
    }
}
