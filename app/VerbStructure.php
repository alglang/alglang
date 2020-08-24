<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class VerbStructure extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Configuration
    |--------------------------------------------------------------------------
    |
    */

    public $guarded = [];

    /*
    |--------------------------------------------------------------------------
    | Constructors
    |--------------------------------------------------------------------------
    |
    */

    public static function fromSearchQuery(array $query): self
    {
        $params = [
            'class_abv' => isset($query['classes']) ? $query['classes'][0] : null,
            'order_name' => isset($query['orders']) ? $query['orders'][0] : null,
            'mode_name' => isset($query['modes']) ? $query['modes'][0] : null
        ];

        foreach (['subject', 'primary_object', 'secondary_object'] as $feature) {
            if (isset($query[$feature]) && !$query[$feature]) {
                continue;
            }

            if (!(isset($query["{$feature}_persons"])
                || isset($query["{$feature}_numbers"])
                || isset($query["{$feature}_obviative_codes"])
            )) {
                $params["{$feature}_name"] = '?';
                continue;
            }

            $featureModel = new Feature([
                'person' => isset($query["{$feature}_persons"]) ? $query["{$feature}_persons"][0] : null,
                'number' => isset($query["{$feature}_numbers"]) ? $query["{$feature}_numbers"][0] : null,
                'obviative_code' => isset($query["{$feature}_obviative_codes"]) ? $query["{$feature}_obviative_codes"][0] : null
            ]);

            $params["{$feature}_name"] = $featureModel->name;
        }

        return new self($params);
    }

    /*
    |--------------------------------------------------------------------------
    | Attribute accessors
    |--------------------------------------------------------------------------
    |
    */

    public function getFeatureStringAttribute(): string
    {
        $string = (string) $this->subject_name;

        if ($this->primary_object_name) {
            $string .= "→{$this->primary_object_name}";
        }

        if ($this->secondary_object_name) {
            $string .= "+{$this->secondary_object_name}";
        }

        return $string;
    }

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    |
    */

    public function matchesStructure(self $other): bool
    {
        $properties = [
            'class_abv',
            'order_name',
            'mode_name',
            'subject_name',
            'primary_object_name',
            'secondary_object_name'
        ];

        $booleanProperties = [
            'is_negative',
            'is_diminutive'
        ];

        foreach ($properties as $property) {
            $ownValue = $this->$property;
            if ($ownValue !== '?' && $ownValue !== $other->$property) {
                return false;
            }
        }

        foreach ($booleanProperties as $property) {
            $ownValue = $this->$property;
            if ($ownValue !== null && $ownValue !== $other->$property) {
                return false;
            }
        }

        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    |
    */

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
