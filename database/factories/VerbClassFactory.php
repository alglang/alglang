<?php

use App\VerbClass;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(VerbClass::class, function (Faker $faker) {
    return [
        'abv' => $faker->unique()->lexify('??')
    ];
});
