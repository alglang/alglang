<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Gloss;
use Faker\Generator as Faker;

$factory->define(Gloss::class, function (Faker $faker) {
    return [
        'name' => 'Foo bar'
    ];
});
