<?php

use App\Account;
use App\Invoice;
use App\Payment;
use Faker\Generator;

$factory->define(Payment::class, function (Generator $faker) {

    $random_id = Account::all(['id'])->random()->id;
    $invoice = Invoice::where('account_id', $random_id)->first();

    return [
        'invoice_id' => $invoice->id,
        'payment_date' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'amount' => $faker->randomFloat($nbMaxDecimals = 2, $min = 1, $max = $invoice->remaining_balance),
        'percent_rate' => $faker->numberBetween($min = 1, $max = 100),
        'comment' => $faker->sentence($nbWords = 6, $variableNbWords = true),
        'created_by' => 'admin',
    ];
});
