<?php

use App\Models\Gloss;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Gloss::class, function (Faker $faker) {
    return [
        'abv' => 'DGN',
        'name' => 'Dummy gloss name'
    ];
});
