<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class VerbStructure extends Model
{
    public $guarded = [];

    public static function fromSearchQuery(array $query): self
    {
        $params = [];

        if (isset($query['classes'])) {
            $params['class_abv'] = $query['classes'][0];
        }

        if (isset($query['orders'])) {
            $params['order_name'] = $query['orders'][0];
        }

        if (isset($query['modes'])) {
            $params['mode_name'] = $query['modes'][0];
        }

        if (isset($query['subject_persons'])
            || isset($query['subject_numbers'])
            || isset($query['subject_obviative_codes'])
        ) {
            $subject = new Feature([
                'person' => isset($query['subject_persons']) ? $query['subject_persons'][0] : null,
                'number' => isset($query['subject_numbers']) ? $query['subject_numbers'][0] : null,
                'obviative_code' => isset($query['subject_obviative_codes']) ? $query['subject_obviative_codes'][0] : null
            ]);
            $params['subject_name'] = $subject->name;
        } else {
            $params['subject_name'] = '?';
        }

        if (isset($query['primary_object']) && !$query['primary_object']) {
            $params['primary_object'] = null;
        } elseif (isset($query['primary_object_persons'])
            || isset($query['primary_object_numbers'])
            || isset($query['primary_object_obviative_codes'])
        ) {
            $primaryObject = new Feature([
                'person' => isset($query['primary_object_persons']) ? $query['primary_object_persons'][0] : null,
                'number' => isset($query['primary_object_numbers']) ? $query['primary_object_numbers'][0] : null,
                'obviative_code' => isset($query['primary_object_obviative_codes']) ? $query['primary_object_obviative_codes'][0] : null
            ]);
            $params['primary_object_name'] = $primaryObject->name;
        } else {
            $params['primary_object_name'] = '?';
        }

        if (isset($query['secondary_object']) && !$query['secondary_object']) {
            $params['secondary_object'] = null;
        } elseif (isset($query['secondary_object_persons'])
            || isset($query['secondary_object_numbers'])
            || isset($query['secondary_object_obviative_codes'])
        ) {
            $secondaryObject = new Feature([
                'person' => isset($query['secondary_object_persons']) ? $query['secondary_object_persons'][0] : null,
                'number' => isset($query['secondary_object_numbers']) ? $query['secondary_object_numbers'][0] : null,
                'obviative_code' => isset($query['secondary_object_obviative_codes']) ? $query['secondary_object_obviative_codes'][0] : null
            ]);
            $params['secondary_object_name'] = $secondaryObject->name;
        } else {
            $params['secondary_object_name'] = '?';
        }

        $structure = new self($params);

        return $structure;
    }

    public function getFeatureStringAttribute(): string
    {
        $string = (string)$this->subject_name;

        if ($this->primary_object_name) {
            $string .= "→{$this->primary_object_name}";
        }

        if ($this->secondary_object_name) {
            $string .= "+{$this->secondary_object_name}";
        }

        return $string;
    }

    public function subject(): Relation
    {
        return $this->belongsTo(Feature::class);
    }

    public function primaryObject(): Relation
    {
        return $this->belongsTo(Feature::class);
    }

    public function secondaryObject(): Relation
    {
        return $this->belongsTo(Feature::class);
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
