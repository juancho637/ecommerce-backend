<?php

namespace Tests\Feature\Api\Country;

use Tests\TestCase;
use App\Models\Country;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowCountryTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function testGetOneCountry()
    {
        $country = Country::all()->random();

        $response = $this->json('GET', route('api.v1.countries.show', [$country]));

        $response->assertStatus(200)->assertJson([
            'data' => [
                'id' => $country->id,
                'name' => $country->name,
                'short_name' => $country->short_name,
                'phone_code' => $country->phone_code,
            ]
        ]);
    }
}
