<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\VerbClass;
use Faker\Generator as Faker;

$factory->define(VerbClass::class, function (Faker $faker) {
    return [
        'abv' => 'TA'
    ];
});
