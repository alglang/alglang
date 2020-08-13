<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class VerbStructure extends Model
{
    public function getArgumentStringAttribute(): string
    {
        $string = $this->subject->name;

        if ($this->primaryObject) {
            $string .= "→{$this->primaryObject->name}";
        }

        if ($this->secondaryObject) {
            $string .= "+{$this->secondaryObject->name}";
        }

        return $string;
    }

    public function subject(): Relation
    {
        return $this->belongsTo(VerbFeature::class);
    }

    public function primaryObject(): Relation
    {
        return $this->belongsTo(VerbFeature::class);
    }

    public function secondaryObject(): Relation
    {
        return $this->belongsTo(VerbFeature::class);
    }

    public function class(): Relation
    {
        return $this->belongsTo(VerbClass::class);
    }

    public function order(): Relation
    {
        return $this->belongsTo(VerbOrder::class);
    }

    public function mode(): Relation
    {
        return $this->belongsTo(VerbMode::class);
    }
}
