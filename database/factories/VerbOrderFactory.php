<?php

use App\Models\VerbOrder;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(VerbOrder::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->word
    ];
});
