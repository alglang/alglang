<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Language;
use App\VerbClass;
use App\VerbFeature;
use App\VerbForm;
use App\VerbMode;
use App\VerbOrder;
use Faker\Generator as Faker;

$factory->define(VerbForm::class, function (Faker $faker) {
    return [
        'shape' => 'V-test',
        'language_id' => factory(Language::class),
        'class_id' => factory(VerbClass::class),
        'order_id' => factory(VerbOrder::class),
        'mode_id' => factory(VerbMode::class),
        'subject_id' => factory(VerbFeature::class)
    ];
});
