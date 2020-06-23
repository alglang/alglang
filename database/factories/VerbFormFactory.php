<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\VerbClass;
use App\VerbFeature;
use App\VerbForm;
use App\VerbMode;
use App\VerbOrder;
use Faker\Generator as Faker;

$factory->define(VerbForm::class, function (Faker $faker) {
    return [
        'class_id' => factory(VerbClass::class)->create(),
        'order_id' => factory(VerbOrder::class)->create(),
        'mode_id' => factory(VerbMode::class)->create(),
        'subject_id' => factory(VerbFeature::class)->create()
    ];
});
