<?php

use App\Email;
use App\Member;
use Faker\Generator;

$factory->define(Email::class, function (Generator $faker) {
    return [
        'email_address' => $faker->safeEmail,
        'member_id' => function () {
            return Member::first()->id ?: factory(Member::class)->create()->id;
        },
    ];
});
