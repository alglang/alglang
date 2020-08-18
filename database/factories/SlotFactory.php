<?php

use App\Slot;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Slot::class, function (Faker $faker) {
    return [
        'abv' => 'TS',
        'name' => 'theme sign'
    ];
});
