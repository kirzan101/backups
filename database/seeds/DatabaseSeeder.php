<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(MembershipTypesTableSeeder::class);
        $this->call(UserGroupTableSeeder::class);
        $this->call(ModulesTableSeeder::class);
        $this->call(DestinationsTableSeeder::class);
        $this->call(ConsultantsTableSeeder::class);
        //$this->call(AccountsTableSeeder::class);
        $this->call(MembersTableSeeder::class);
        //$this->call(AccountMemberTableSeeder::class);
        $this->call(VouchersTableSeeder::class);
        //$this->call(PaymentsTableSeeder::class);
    }
}
