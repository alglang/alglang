<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Feature;
use App\VerbClass;
use App\VerbMode;
use App\VerbOrder;
use App\VerbStructure;
use Faker\Generator as Faker;

$factory->define(VerbStructure::class, function (Faker $faker) {
    return [
        'class_id' => factory(VerbClass::class),
        'order_id' => factory(VerbOrder::class),
        'mode_id' => factory(VerbMode::class),
        'subject_id' => factory(Feature::class)
    ];
});
