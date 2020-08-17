<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Feature;
use App\NominalParadigm;
use App\NominalStructure;
use Faker\Generator as Faker;

$factory->define(NominalStructure::class, function (Faker $faker) {
    return [
        'pronominal_feature_id' => factory(Feature::class),
        'nominal_feature_id' => factory(Feature::class),
        'paradigm_id' => factory(NominalParadigm::class)
    ];
});
