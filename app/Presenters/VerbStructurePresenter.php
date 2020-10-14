<?php

namespace App\Presenters;

trait VerbStructurePresenter
{
    public function getNameAttribute(): string
    {
        $name = "{$this->featureString} {$this->class_abv} {$this->order_name} {$this->mode_name}";
        $addon = [];

        if ($this->is_negative) {
            $addon[] = 'negative';
        }

        if ($this->is_diminutive) {
            $addon[] = 'diminutive';
        }

        if ($this->is_absolute) {
            $addon[] = 'absolute';
        } elseif ($this->is_absolute === false) {
            $addon[] = 'objective';
        }

        if (count($addon) > 0) {
            $name .= ' (' . implode(', ', $addon) . ')';
        }

        return $name;
    }

    public function getFeatureStringAttribute(): string
    {
        $string = $this->convertFeatureToString($this->subject_name, 'subject');

        if ($this->primary_object_name) {
            $pobj = $this->convertFeatureToString(
                $this->primary_object_name,
                'primary object'
            );
            $string .= "→{$pobj}";
        }

        if ($this->secondary_object_name) {
            $sobj = $this->convertFeatureToString(
                $this->secondary_object_name,
                'secondary object'
            );
            $string .= "+{$sobj}";
        }

        return $string;
    }

    protected function convertFeatureToString(string $feature, string $headCode): string
    {
        if ($this->head === $headCode) {
            return "<u>{$feature}</u>";
        }
        return $feature;
    }
}
