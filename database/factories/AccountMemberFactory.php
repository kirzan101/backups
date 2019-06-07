<?php

use App\Account;
use App\AccountMember;
use App\Member;
use Faker\Generator;

$factory->define(AccountMember::class, function (Generator $faker) {
    $m_type = Member::first()->membership_type ?: factory(Member::class)->create()->membership_type;
    $random_id = Account::where('membership_type', $m_type)->get()->random()->id;

    //Check if account already has 4 members
    $count = AccountMember::where('account_id', $random_id)->get()->count();
    if ($count >= 4) {
        $random_id = Account::where('membership_type', $m_type)->get()->random()->id;
    }

    return [
        'account_id' => $random_id,
        'member_id' => function () {
            return Member::first()->id;
        },
    ];
});
