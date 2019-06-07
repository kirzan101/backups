<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'first_name' => 'Aron Cloyd',
                'last_name' => 'Sazon',
                'email' => 'aron.sazon@astoria.com.ph',
                'user_group' => 1,
                'username' => 'acsazon',
                'password' => bcrypt('P@ssw0rd'),
                'department' => 'ICT',
                'created_by' => 'system',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'first_name' => 'Rae',
                'last_name' => 'Pangilinan',
                'email' => 'rae.pangilinan@astoria.com.ph',
                'user_group' => 1,
                'username' => 'raepangilinan',
                'password' => '$2y$10$lMp5EySRkWiDa.DbL3.gp.wEMhtRfrZdJO6sBTrsYUWTWCU0O/h2m',
                'department' => 'ICT',
                'created_by' => 'system',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'first_name' => 'Yannah',
                'last_name' => 'Datu',
                'email' => 'yannah.datu@astoria.com.ph',
                'user_group' => 1,
                'username' => 'yannahdatu',
                'password' => '$2y$10$uAbcxi/Mak.J5U.bA1fdb.KgEkETTkqqoZQxOmeQ4tVg0DGHnr556',
                'department' => 'ICT',
                'created_by' => 'system',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'first_name' => 'Dave',
                'last_name' => 'Malazzab',
                'email' => 'acctg@stellarvacationclub.com',
                'user_group' => 3,
                'username' => 'davemalazzab',
                'password' => '$2y$10$oRIb75fZ8iRV45v6oFVK.udptk/yH8YEdm05yMYvHC2X0g/7kMP1G',
                'department' => 'Club Accounting',
                'created_by' => 'system',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'first_name' => 'Andy',
                'last_name' => 'Hernandez',
                'email' => 'andy.hernandez@avlci.com.ph',
                'user_group' => 3,
                'username' => 'andyhernandez',
                'password' => '$2y$10$oRIb75fZ8iRV45v6oFVK.udptk/yH8YEdm05yMYvHC2X0g/7kMP1G',
                'department' => 'Club Accounting',
                'created_by' => 'system',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'first_name' => 'Romerto',
                'last_name' => 'Palacio',
                'email' => 'romerto.palacio@astoria.com.ph',
                'user_group' => 4,
                'username' => 'romerpalacio',
                'password' => '$2y$10$oRIb75fZ8iRV45v6oFVK.udptk/yH8YEdm05yMYvHC2X0g/7kMP1G',
                'department' => 'Accounting',
                'created_by' => 'system',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'first_name' => 'Eunice',
                'last_name' => 'Dollete',
                'email' => 'eunice.dollete@astoria.com.ph',
                'user_group' => 1,
                'username' => 'eadollete',
                'password' => bcrypt('P@ssw0rd'),
                'department' => 'ICT',
                'created_by' => 'system',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'first_name' => 'Ron',
                'last_name' => 'Romarate',
                'email' => 'ron.romarate@astoria.com.ph',
                'user_group' => 1,
                'username' => 'ronromarate',
                'password' => bcrypt('P@ssw0rd'),
                'department' => 'ICT',
                'created_by' => 'system',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'first_name' => 'Christian Kim',
                'last_name' => 'Escamilla',
                'email' => 'christian.escamilla@astoria.com.ph',
                'user_group' => 1,
                'username' => 'christiankim',
                'password' => bcrypt('P@ssw0rd'),
                'department' => 'ICT',
                'created_by' => 'system',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
