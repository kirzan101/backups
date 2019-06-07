<?php

use App\Account;
use Illuminate\Database\Seeder;

class AccountsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // factory(Account::class, 90)
        //     ->create()
        //     ->each(function ($a) {
        //         $a->invoice()->save(factory(App\Invoice::class)->make());
        //     });
        factory(Account::class, 90)->create();
    }
}
