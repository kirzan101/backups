<?php

use App\Consultant;
use Faker\Generator;

$factory->define(Consultant::class, function (Generator $faker) {
    return [
        'name' => $faker->name($gender = null),
    ];
});
