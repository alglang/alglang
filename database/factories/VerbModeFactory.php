<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\VerbMode;
use Faker\Generator as Faker;

$factory->define(VerbMode::class, function (Faker $faker) {
    return [
        'name' => 'Indicative'
    ];
});
