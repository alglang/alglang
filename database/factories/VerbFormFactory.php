<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Language;
use App\VerbForm;
use App\VerbStructure;
use Faker\Generator as Faker;

$factory->define(VerbForm::class, function (Faker $faker) {
    return [
        'shape' => 'V-test',
        'language_id' => factory(Language::class),
        'structure_id' => factory(VerbStructure::class)
    ];
});
