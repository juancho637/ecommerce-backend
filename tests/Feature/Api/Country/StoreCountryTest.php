<?php

namespace Tests\Feature\Api\Country;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreCountryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function testCreateCountry()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $name = $this->faker->sentence(1, false);
        $shortName = 'US';
        $phoneCode = '+1';

        $response = $this->json('POST', route('api.v1.countries.store'), [
            'name' => $name,
            'short_name' => $shortName,
            'phone_code' => $phoneCode,
        ]);

        $response->assertStatus(201)->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'short_name',
                'phone_code',
            ]
        ])->assertJson([
            'data' => [
                'name' => $name,
                'short_name' => $shortName,
                'phone_code' => $phoneCode,
            ]
        ]);
    }
}
