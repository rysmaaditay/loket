<?php

use Illuminate\Database\Seeder;

class EventTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('events')->insert([
            [
                'name' => 'Jatim Fair Expo',
                'location_id' => 1,
                'start_at' => '2018-10-20 20:00',
                'end_at' => '2018-10-21 20:00'
            ]
        ]);
    }
}
