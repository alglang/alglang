<?php

use App\Models\Group;
use App\Models\Language;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Language::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->words(3, true),
        'code' => $faker->unique()->lexify('???'),
        'group_name' => factory(Group::class)
    ];
});
