<?php

use App\NominalParadigmType;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(NominalParadigmType::class, function (Faker $faker) {
    return [
        'name' => 'Factory Paradigm Type'
    ];
});
