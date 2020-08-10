<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Source;
use Faker\Generator as Faker;

$factory->define(Source::class, function (Faker $faker) {
    return [
        'author' => $faker->lastName,
        'year' => $faker->year,
        'full_citation' => '<p>Joe Brown. 1984. <i>This is the title</i>. Winnipeg: Some Publisher.</p>'
    ];
});
