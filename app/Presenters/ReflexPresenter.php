<?php

namespace App\Presenters;

trait ReflexPresenter
{
    public function getFormattedNameAttribute(): string
    {
        $phonemeLanguage = $this->phoneme->language->name;
        $reflexLanguage = $this->reflex->language->name;
        $phonemeShape = $this->phoneme->formatted_shape;
        $reflexShape = $this->reflex->formatted_shape;

        return "<span>{$phonemeShape} ({$phonemeLanguage}) &gt; {$reflexShape} ({$reflexLanguage})</span>";
    }
}
