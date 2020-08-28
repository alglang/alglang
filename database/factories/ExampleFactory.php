<?php

use App\Models\Example;
use App\Models\Form;
use App\Models\Morpheme;
use App\Models\NominalForm;
use App\Models\VerbForm;
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

$factory->state(Example::class, 'verb', function (Faker $faker) {
    return [
        'form_id' => factory(VerbForm::class)
    ];
});

$factory->state(Example::class, 'nominal', function (Faker $faker) {
    return [
        'form_id' => factory(NominalForm::class)
    ];
});
