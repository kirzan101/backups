<?php

use App\Consultant;
use Illuminate\Database\Seeder;

class ConsultantsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //factory(Consultant::class, 30)->create();
        $consultants = DB::table('member_profile')->select('consultant')->distinct()->get();

        foreach ($consultants as $c) {
            $consultant = new Consultant;
            $consultant->name = $c->consultant;
            $consultant->save();
        }
    }
}
