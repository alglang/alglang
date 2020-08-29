<?php

namespace App\Presenters;

trait VerbStructurePresenter
{
    public function getFeatureStringAttribute(): string
    {
        $string = (string) $this->subject_name;

        if ($this->head === 'subject') {
            $string = "<u>{$string}</u>";
        }

        if ($this->primary_object_name) {
            $pobj = $this->primary_object_name;
            if ($this->head === 'primary object') {
                $pobj = "<u>{$pobj}</u>";
            }
            $string .= "→{$pobj}";
        }

        if ($this->secondary_object_name) {
            $sobj = $this->secondary_object_name;
            if ($this->head === 'secondary object') {
                $sobj = "<u>{$sobj}</u>";
            }
            $string .= "+{$sobj}";
        }

        return $string;
    }
}
