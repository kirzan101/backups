<?php

use App\MembershipType;
use Illuminate\Database\Seeder;

class MembershipTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            ['type' => 'Green Card'],
            ['type' => 'Platinum Card'],
        ];

        foreach ($types as $type) {
            MembershipType::create($type);
        }
    }
}
