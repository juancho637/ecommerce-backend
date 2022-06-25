<?php

namespace Tests\Feature\Api\City;

use Tests\TestCase;
use App\Models\City;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowCityTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function testGetOneCity()
    {
        $city = City::all()->random();

        $response = $this->json('GET', route('api.v1.cities.show', [$city]));

        $response->assertStatus(200)->assertJson([
            'data' => [
                'id' => $city->id,
                'name' => $city->name,
            ]
        ]);
    }
}
