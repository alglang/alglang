<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Language;
use App\Morpheme;
use App\Slot;
use Faker\Generator as Faker;

$factory->define(Morpheme::class, function (Faker $faker) {
    return [
        'shape' => '-ak',
        'language_id' => factory(Language::class),
        'slot_id' => factory(Slot::class)
    ];
});
