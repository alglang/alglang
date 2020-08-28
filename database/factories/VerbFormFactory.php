<?php

use App\Models\Language;
use App\Models\VerbForm;
use App\Models\VerbStructure;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(VerbForm::class, function (Faker $faker) {
    return [
        'shape' => 'V-factory',
        'language_code' => factory(Language::class),
        'structure_type' => VerbStructure::class,
        'structure_id' => factory(VerbStructure::class)
    ];
});
