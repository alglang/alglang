<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\NominalParadigmType;
use Faker\Generator as Faker;

$factory->define(NominalParadigmType::class, function (Faker $faker) {
    return [
        'name' => 'Factory Paradigm Type'
    ];
});
