<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\NominalParadigm;
use Faker\Generator as Faker;

$factory->define(NominalParadigm::class, function (Faker $faker) {
    return [
        'name' => 'Factory paradigm'
    ];
});
