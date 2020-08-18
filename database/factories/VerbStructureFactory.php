<?php

use App\Feature;
use App\VerbClass;
use App\VerbMode;
use App\VerbOrder;
use App\VerbStructure;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(VerbStructure::class, function (Faker $faker) {
    return [
        'class_abv' => factory(VerbClass::class),
        'order_name' => factory(VerbOrder::class),
        'mode_name' => factory(VerbMode::class),
        'subject_name' => factory(Feature::class)
    ];
});
