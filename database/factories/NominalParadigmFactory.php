<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Language;
use App\NominalParadigm;
use App\NominalParadigmType;
use Faker\Generator as Faker;

$factory->define(NominalParadigm::class, function (Faker $faker) {
    return [
        'name' => 'Factory paradigm',
        'paradigm_type_id' => factory(NominalParadigmType::class),
        'language_id' => factory(Language::class)
    ];
});
