<?php

use App\ContactNumber;
use App\Member;
use Faker\Generator;

$factory->define(ContactNumber::class, function (Generator $faker) {
    return [
        'member_id' => function () {
            return Member::first()->id ?: factory(Member::class)->create()->id;
        },
        'contact_number' => '09' . $faker->randomNumber($nbDigits = 9, $strict = true),
        'contact_type' => $faker->randomElement([
            'home', 'work',
        ]),
    ];
});
