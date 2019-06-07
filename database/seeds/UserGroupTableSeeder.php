<?php

use App\UserGroup;
use Illuminate\Database\Seeder;

class UserGroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_groups = [
            [
                'user_group_name' => 'ICT Admin',
                'description' => 'ICT Admin',
                'modules_access' => '1,2,3,4,5,6,7,8,9,10,11',
                'modules_permissions' => 'rcu,rcu,rcu,rcu,rcu,rcu,rcu,rcu,rcu,rcu,rcu',
                'created_by' => 'system',
            ],
            [
                'user_group_name' => 'Global Admin',
                'description' => 'Global Admin',
                'modules_access' => '1,2,3,4,5,6,7,8,9,10,11',
                'modules_permissions' => 'rcu,rcu,rcu,rcu,rcu,rcu,rcu,rcu,rcu,rcu,rcu',
                'created_by' => 'system',
            ],
            [
                'user_group_name' => 'Club Accounting',
                'description' => 'Club Accounting Group',
                'modules_access' => '1,2,3,4,6,11',
                'modules_permissions' => 'rcu,rcu,rcu,rcu,rcu,rcu',
                'created_by' => 'system',
            ],
            [
                'user_group_name' => 'Accounting',
                'description' => 'Accounting Group',
                'modules_access' => '1,2,3,4,6,11',
                'modules_permissions' => 'rcu,rcu,rcu,rcu,rcu,rcu',
                'created_by' => 'system',
            ],
        ];

        foreach ($user_groups as $ug) {
            UserGroup::create($ug);
        }
    }
}
