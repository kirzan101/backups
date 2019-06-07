<?php

use App\Destination;
use Illuminate\Database\Seeder;

class DestinationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $destinations = [
            ['code' => 'CIBP', 'destination_name' => 'RCI', 'remarks' => '1 Week Complimentary Stay at an RCI Affiliated Resort', 'created_by' => '-'],
            ['code' => 'AGH', 'destination_name' => 'Astoria Greenbelt', 'remarks' => 'Overnight Accommodation', 'created_by' => '-'],
            ['code' => 'AGB', 'destination_name' => 'Astoria Greenbelt', 'remarks' => 'Breakfast for 2 Pax', 'created_by' => '-'],
            ['code' => 'APT', 'destination_name' => 'Astoria Palawan Waterpark', 'remarks' => 'Free Admission for 4 Pax', 'created_by' => '-'],
            ['code' => 'APC', 'destination_name' => 'Astoria Palawan / Astoria Current', 'remarks' => 'Overnight Accommodation', 'created_by' => '-'],
            ['code' => 'ACB', 'destination_name' => 'Astoria Palawan / Astoria Current', 'remarks' => 'Breakfast for 2 Pax', 'created_by' => '-'],
            ['code' => 'APH', 'destination_name' => 'Astoria Plaza', 'remarks' => 'Overnight Accommodation', 'created_by' => '-'],
            ['code' => 'APB', 'destination_name' => 'Astoria Plaza', 'remarks' => 'Breakfast for 2 Pax', 'created_by' => '-'],
        ];

        foreach ($destinations as $des) {
            Destination::create($des);
        }
    }
}
