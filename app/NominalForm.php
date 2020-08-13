<?php

namespace App;

class NominalForm extends Form
{
    public $table = 'forms';

    public function getMorphClass()
    {
        return Form::class;
    }

    public function getUrlAttribute(): string
    {
        return "/languages/{$this->language->slug}/nominal-forms/{$this->slug}";
    }
}
