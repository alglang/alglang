<?php

use App\Example;
use App\Form;
use App\Morpheme;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Example::class, function (Faker $faker) {
    return [
        'shape' => 'exampleshape',
        'stem_id' => factory(Morpheme::class),
        'form_id' => factory(Form::class),
        'translation' => '<p>factory translation</p>'
    ];
});
