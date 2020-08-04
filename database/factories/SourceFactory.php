<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Source;
use Faker\Generator as Faker;

$factory->define(Source::class, function (Faker $faker) {
    return [
        'author' => 'John Doe',
        'year' => 2020,
        'full_citation' => '<p>Joe Brown. 1984. <i>This is the title</i>. Winnipeg: Some Publisher.</p>'
    ];
});
