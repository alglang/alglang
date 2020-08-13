<?php

namespace App;

class VerbForm extends Form
{
    public $table = 'forms';

    public function getMorphClass()
    {
        return Form::class;
    }
}
