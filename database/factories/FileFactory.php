<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\File;
use Faker\Generator as Faker;

$factory->define(File::class, function (Faker $faker) {
    return [
        'hash' => $faker->uuid . $faker->randomElement(['.png', '.jpg']),
        'name' => $faker->words(3, true),
    ];
});
