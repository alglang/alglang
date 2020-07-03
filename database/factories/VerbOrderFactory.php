<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\VerbOrder;
use Faker\Generator as Faker;

$factory->define(VerbOrder::class, function (Faker $faker) {
    return [
        'name' => 'Conjunct'
    ];
});
