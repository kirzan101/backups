<?php

use App\Account;
use Faker\Generator;

$factory->define(Account::class, function (Generator $faker) {
    return [
        'membership_type' => $faker->numberBetween($min = 1, $max = 2),
        'sales_deck' => $faker->iban($countryCode = null),
        'consultant_id' => App\Consultant::all(['id'])->random(),
        'created_by' => 'system',
    ];
});
