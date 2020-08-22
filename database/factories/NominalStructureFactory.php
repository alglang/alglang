<?php

use App\Feature;
use App\NominalParadigm;
use App\NominalStructure;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(NominalStructure::class, function (Faker $faker) {
    return [
        'pronominal_feature_name' => factory(Feature::class),
        'nominal_feature_name' => factory(Feature::class),
        'paradigm_id' => factory(NominalParadigm::class)
    ];
});
