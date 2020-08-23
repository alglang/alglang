<?php

use App\Language;
use App\NominalParadigm;
use App\NominalParadigmType;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(NominalParadigm::class, function (Faker $faker) {
    return [
        'name' => 'Factory paradigm',
        'paradigm_type_name' => factory(NominalParadigmType::class),
        'language_code' => factory(Language::class)
    ];
});
