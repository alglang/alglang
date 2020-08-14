<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\NominalFeature;
use Faker\Generator as Faker;

$factory->define(NominalFeature::class, function (Faker $faker) {
    return [
        'name' => '1s'
    ];
});
