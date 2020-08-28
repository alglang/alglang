<?php

use App\Models\Language;
use App\Models\NominalForm;
use App\Models\NominalStructure;
use App\Models\NominalParadigm;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(NominalForm::class, function (Faker $faker) {
    return [
        'shape' => 'V-test',
        'language_code' => factory(Language::class),
        'structure_type' => NominalStructure::class,
        'structure_id' => function (array $form) {
            return factory(NominalStructure::class)->create([
                'paradigm_id' => factory(NominalParadigm::class)->create([
                    'language_code' => $form['language_code']
                ])->id
            ])->id;
        }
    ];
});
