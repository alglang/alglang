<?php

use App\Language;
use App\Morpheme;
use App\Slot;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Morpheme::class, function (Faker $faker) {
    return [
        'shape' => '-ak',
        'language_code' => factory(Language::class),
        'slot_abv' => factory(Slot::class)
    ];
});
