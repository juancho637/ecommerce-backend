<?php

namespace Tests\Feature\Api\Country;

use Tests\TestCase;
use App\Models\User;
use App\Models\Country;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateCountryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function testUpdateCountry()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $country = Country::all()->random();
        $name = $this->faker->sentence(1, false);

        $response = $this->json('PUT', route('api.v1.countries.update', [$country]), [
            'name' => $name,
        ]);

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'short_name',
                'phone_code',
            ]
        ])->assertJson([
            'data' => [
                'id' => $country->id,
                'name' => $name,
                'short_name' => $country->short_name,
                'phone_code' => $country->phone_code,
            ]
        ]);
    }
}
