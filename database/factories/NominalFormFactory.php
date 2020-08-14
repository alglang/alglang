<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Language;
use App\NominalForm;
use App\NominalStructure;
use Faker\Generator as Faker;

$factory->define(NominalForm::class, function (Faker $faker) {
    return [
        'shape' => 'V-test',
        'language_id' => factory(Language::class),
        'structure_type' => NominalStructure::class,
        'structure_id' => factory(NominalStructure::class)
    ];
});
