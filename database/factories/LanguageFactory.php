<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Group;
use App\Language;
use Faker\Generator as Faker;

$factory->define(Language::class, function (Faker $faker) {
    return [
        'name' => 'Test Language',
        'algo_code' => 'PA',
        'group_id' => factory(Group::class)
    ];
});
