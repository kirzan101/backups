<?php

use App\Account;
use App\AccountMember;
use App\Member;
use Illuminate\Database\Seeder;

class AccountMemberTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Accounts that doesn't have members
        $getAccounts = Account::all('id');

        //Accounts with members
        $getStored = AccountMember::select('account_id')
            ->distinct()
            ->whereIn('account_id', $getAccounts)
            ->get();

        $accounts = array();
        $stored = array();

        foreach ($getAccounts as $acc) {
            array_push($accounts, $acc->id);
        }

        foreach ($getStored as $get) {
            array_push($stored, $get->account_id);
        }

        $toAdd = array_values(array_diff($accounts, $stored));

        foreach ($toAdd as $add) {
            $a_type = Account::where('id', $add)->first()->membership_type;
            $random_id = Member::where('membership_type', $a_type)->get()->random()->id;

            AccountMember::create([
                'account_id' => $add,
                'member_id' => $random_id,
            ]);
        }
    }
}
