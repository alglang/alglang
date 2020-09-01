<?php

namespace App\Traits;

use App\Models\Feature;

trait GeneratesFromSearchQuery
{
    public static function fromSearchQuery(array $query): self
    {
        $params = [
            'class_abv' => isset($query['classes']) ? $query['classes'][0] : null,
            'order_name' => isset($query['orders']) ? $query['orders'][0] : null,
            'mode_name' => isset($query['modes']) ? $query['modes'][0] : null
        ];

        foreach (['subject', 'primary_object', 'secondary_object'] as $feature) {
            $featureName = static::generateFeatureName($feature, $query);

            if ($featureName) {
                $params["{$feature}_name"] = $featureName;
            }
        }

        return new self($params);
    }

    protected static function generateFeatureName(string $feature, array $query): ?string
    {
        if (isset($query[$feature]) && !$query[$feature]) {
            return null;
        }

        $person = isset($query["{$feature}_persons"]) ? $query["{$feature}_persons"][0] : null;
        $number = isset($query["{$feature}_numbers"]) ? $query["{$feature}_numbers"][0] : null;
        $obviativeCode = isset($query["{$feature}_obviative_codes"]) ? $query["{$feature}_obviative_codes"][0] : null;

        if (is_null($person) && is_null($number) && is_null($obviativeCode)) {
            return '?';
        }

        $model = new Feature([
            'person' => $person,
            'number' => $number,
            'obviative_code' => $obviativeCode
        ]);

        return $model->name;
    }
}
