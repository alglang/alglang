<?php

use App\NominalParadigmType;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(NominalParadigmType::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->word,
        'has_pronominal_feature' => true,
        'has_nominal_feature' => true
    ];
});
