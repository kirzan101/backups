<?php

use App\Member;
use Faker\Generator;

$factory->define(Member::class, function (Generator $faker) {

    $x = rand(1, 2);

    switch ($x) {
        case 1:
            $fname = $faker->firstNameMale;
            $gender = 'male';
            break;
        case 2:
            $fname = $faker->firstNameFemale;
            $gender = 'female';
            break;
    }

    return [
        'first_name' => $fname,
        'middle_name' => $faker->lastName,
        'last_name' => $faker->lastName,
        'membership_type' => $faker->numberBetween($min = 1, $max = 2),
        'birthday' => $faker->dateTimeThisCentury->format('Y-m-d'),
        'gender' => $gender,
        'status' => $faker->randomElement([
            'active', 'inactive',
        ]),
        'created_by' => 'system',
    ];
});
