<?php

use App\Address;
use App\Member;
use Faker\Generator;

$factory->define(Address::class, function (Generator $faker) {
    return [
        'member_id' => function () {
            return Member::first()->id ?: factory(Member::class)->create()->id;
        },
        'house_number' => $faker->buildingNumber,
        'subdivision' => $faker->streetName,
        'city' => $faker->city,
        'state' => $faker->state,
        'country' => $faker->country,
        'postal_code' => $faker->postcode,
    ];
});
