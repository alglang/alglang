<?php

use App\Form;
use App\Language;
use App\VerbStructure;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Form::class, function (Faker $faker) {
    return [
        'shape' => 'V-test',
        'language_code' => factory(Language::class),
        'structure_type' => VerbStructure::class,
        'structure_id' => factory(VerbStructure::class)
    ];
});
