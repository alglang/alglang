<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Language;
use App\NominalForm;
use App\NominalStructure;
use App\NominalParadigm;
use Faker\Generator as Faker;

$factory->define(NominalForm::class, function (Faker $faker) {
    return [
        'shape' => 'V-test',
        'language_id' => factory(Language::class),
        'structure_type' => NominalStructure::class,
        'structure_id' => function (array $form) {
            return factory(NominalStructure::class)->create([
                'paradigm_id' => factory(NominalParadigm::class)->create([
                    'language_id' => $form['language_id']
                ])->id
            ])->id;
        }
    ];
});
