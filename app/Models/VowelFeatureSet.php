<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class VowelFeatureSet extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_archiphoneme' => 'bool'
    ];

    public function backness(): Relation
    {
        return $this->belongsTo(VowelBackness::class);
    }

    public function height(): Relation
    {
        return $this->belongsTo(VowelHeight::class);
    }
}
