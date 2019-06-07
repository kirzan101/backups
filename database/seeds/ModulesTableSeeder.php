<?php

use App\Module;
use Illuminate\Database\Seeder;

class ModulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $modules = [
            ['module_name' => 'reports', 'description' => 'display reports', 'url' => '/reports/collection'],
            ['module_name' => 'members', 'description' => 'members', 'url' => '/members'],
            ['module_name' => 'accounts', 'description' => 'accounts of members', 'url' => '/accounts'],
            ['module_name' => 'vouchers', 'description' => 'vouchers of accounts/members', 'url' => '/vouchers'],
            ['module_name' => 'payments', 'description' => 'payments of members to accounts', 'url' => '/payments'],
            ['module_name' => 'redemptions', 'description' => 'redemption for vouchers', 'url' => '/redemptions'],
            ['module_name' => 'users', 'description' => 'users of system', 'url' => '/users'],
            ['module_name' => 'invoice', 'description' => 'invoice', 'url' => '/invoices'],
            ['module_name' => 'audit log', 'description' => 'tracking for system', 'url' => '/audit-log'],
            ['module_name' => 'settings', 'description' => 'system settings', 'url' => '/settings'],
            ['module_name' => 'portals', 'description' => 'switch for member type', 'url' => '/portals'],
        ];

        foreach ($modules as $module) {
            Module::create($module);
        }
    }
}
