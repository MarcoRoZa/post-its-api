<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Group;
use Faker\Generator as Faker;

$factory->define(Group::class, function (Faker $faker) {
    return [
        'uuid' => $faker->uuid,
        'name' => $faker->words(3, true),
        'description' => $faker->sentence,
    ];
});
