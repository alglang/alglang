<?php

namespace App\Models;

use App\Traits\Sourceable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class Rule extends Model
{
    use HasFactory;
    use Sourceable;

    public $incrementing = false;

    protected $primaryKey = 'abv';

    protected $keyType = 'str';

    protected $guarded = [];

    public function getUrlAttribute(): string
    {
        return "/languages/{$this->language_code}/rules/{$this->abv}";
    }

    public function language(): Relation
    {
        return $this->belongsTo(Language::class);
    }

    public function type(): Relation
    {
        return $this->belongsTo(RuleType::class)->withDefault([
            'name' => 'Uncategorized'
        ]);
    }
}
