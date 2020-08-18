<?php

use App\Feature;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Feature::class, function (Faker $faker) {
    return [
        'name' => '1s'
    ];
});
