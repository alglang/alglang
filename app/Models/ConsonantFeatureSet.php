<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class ConsonantFeatureSet extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_archiphoneme' => 'bool'
    ];

    public function place(): Relation
    {
        return $this->belongsTo(ConsonantPlace::class);
    }

    public function manner(): Relation
    {
        return $this->belongsTo(ConsonantManner::class);
    }
}
