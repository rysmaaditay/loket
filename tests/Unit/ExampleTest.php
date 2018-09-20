<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function insertLocation()
    {
        $response = $this->json('POST', '/api/location/create', 
            ['name' => 'Tunjungan Plaza Surabaya']
        );

        $response
            ->assertStatus(200)
            ->assertJson([
                'created' => true,
            ]);
    }
}
