<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Group;
use App\Language;
use Faker\Generator as Faker;

$factory->define(Language::class, function (Faker $faker) {
    return [
        'name' => 'Factory Created Language',
        'algo_code' => 'FCL',
        'group_id' => factory(Group::class)
    ];
});
