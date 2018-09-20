<?php

use Illuminate\Database\Seeder;

class TicketTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ticket_types')->insert([
            [
                'name' => 'Jatim Expo Golden Ticket',
                'event_id' => 1,
                'price' => 100000,
                'quota' => 1000
            ],
            [
                'name' => 'Jatim Expo Silver Ticket',
                'event_id' => 1,
                'price' => 50000,
                'quota' => 2000
            ]
        ]);
    }
}
