<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\NominalFeature;
use App\NominalParadigm;
use App\NominalStructure;
use Faker\Generator as Faker;

$factory->define(NominalStructure::class, function (Faker $faker) {
    return [
        'pronominal_feature_id' => factory(NominalFeature::class),
        'nominal_feature_id' => factory(NominalFeature::class),
        'paradigm_id' => factory(NominalParadigm::class)
    ];
});
