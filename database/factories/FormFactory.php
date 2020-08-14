<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Form;
use App\Language;
use App\VerbStructure;
use Faker\Generator as Faker;

$factory->define(Form::class, function (Faker $faker) {
    return [
        'shape' => 'V-test',
        'language_id' => factory(Language::class),
        'structure_type' => VerbStructure::class,
        'structure_id' => factory(VerbStructure::class)
    ];
});

$factory->state(Form::class, 'verb', function (Faker $faker) {
    return [];
});

$factory->state(Form::class, 'nominal', function (Faker $faker) {
    return [
        'structure_type' => 'TODO'  // TODO: Update this when nominals exist
    ];
});
