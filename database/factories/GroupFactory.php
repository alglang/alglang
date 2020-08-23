<?php

use App\Group;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Group::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->word
    ];
});
