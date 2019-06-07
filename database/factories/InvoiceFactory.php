<?php

use App\Account;
use App\Invoice;
use Faker\Generator;

$factory->define(Invoice::class, function (Generator $faker) {
    $principal = $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 900000);
    $downpayment = $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = $principal);
    $balance = $principal - $downpayment;
    $status = 'draft';

    if ($balance > 0 && $balance < $principal) {
        $status = 'partial';
    } elseif ($balance == 0) {
        $status = 'full';
    }

    return [
        'account_id' => function () {
            return Account::first()->id ?: factory(Account::class)->create()->id;
        },
        'principal_amount' => $principal,
        'downpayment' => $downpayment,
        'total_paid_amount' => $downpayment,
        'remaining_balance' => $balance,
        'status' => $status,
    ];
});
