<?php

use App\VerbMode;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(VerbMode::class, function (Faker $faker) {
    return [
        'name' => 'Indicative'
    ];
});
