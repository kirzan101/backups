<?php

use App\Account;
use App\Voucher;
use Faker\Generator;

$factory->define(Voucher::class, function (Generator $faker) {
    $date_issued = $faker->date($format = 'Y-m-d', $max = 'now');
    $valid_from = $faker->dateTimeBetween($startDate = $date_issued, $endDate = 'now', $timezone = 'Asia/Manila');
    $valid_to = $faker->dateTimeBetween($startDate = $valid_from, $endDate = 'now', $timezone = 'Asia/Manila');
    $date_redeemed = $faker->dateTimeBetween($startDate = $valid_from, $endDate = $valid_to, $timezone = 'Asia/Manila');
    $check_in = $faker->dateTimeBetween($startDate = $valid_from, $endDate = $valid_to, $timezone = 'Asia/Manila');
    $check_out = $faker->dateTimeBetween($startDate = $check_in, $endDate = $valid_to, $timezone = 'Asia/Manila');

    $guest_first_name = $faker->firstName($gender = null);
    $guest_middle_name = $faker->lastName;
    $guest_last_name = $faker->lastName;

    $x = rand(1, 4);

    switch ($x) {
        case 1:
            $status = 'unused';
            $date_redeemed = $check_in = $check_out = $guest_first_name = $guest_middle_name = $guest_last_name = null;
            break;
        case 2:
            $status = 'canceled';
            $date_redeemed = $check_in = $check_out = $guest_first_name = $guest_middle_name = $guest_last_name = null;
            break;
        case 3:
            $status = 'forfeited';
            $date_redeemed = $check_in = $check_out = $guest_first_name = $guest_middle_name = $guest_last_name = null;
            break;
        case 4:
            $status = 'redeemed';
            break;
    }

    $random_id = Account::all(['id'])->random()->id;

    return [
        'account_id' => $random_id,
        'card_number' => $faker->iban($countryCode = null),
        'status' => $status,
        'date_issued' => $date_issued,
        'valid_from' => $valid_from,
        'valid_to' => $valid_to,
        'remarks' => $faker->realText($maxNbChars = 50, $indexSize = 2),
        'destination_id' => $faker->numberBetween($min = 1, $max = 4),
        'date_redeemed' => $date_redeemed,
        'check_in' => $check_in,
        'check_out' => $check_out,
        'guest_first_name' => $guest_first_name,
        'guest_middle_name' => $guest_middle_name,
        'guest_last_name' => $guest_last_name,
        'created_by' => 'admin',
    ];
});
