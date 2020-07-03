<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\VerbFeature;
use Faker\Generator as Faker;

$factory->define(VerbFeature::class, function (Faker $faker) {
    return [
        'name' => '3s'
    ];
});
