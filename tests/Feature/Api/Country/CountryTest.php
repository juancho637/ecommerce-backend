<?php

namespace Tests\Feature\Api\Country;

use App\Models\User;
use App\Models\Status;
use App\Models\Country;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Api\ApiTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CountryTest extends ApiTestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function testGetAllCountries()
    {
        $response = $this->json('GET', route('api.v1.countries.index'));

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'name',
                    'short_name',
                    'phone_code',
                ]
            ]
        ]);
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

        $response->assertStatus(200)->assertJsonStructure([
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

    public function testDeleteCountry()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $country = Country::all()->random();
        $disabledStatus = Status::disabled()->first();

        $response = $this->json('DELETE', route('api.v1.countries.destroy', [
            $country,
            'include' => 'status'
        ]));

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
                'name' => $country->name,
                'short_name' => $country->short_name,
                'phone_code' => $country->phone_code,
                'status' => [
                    'id' => $disabledStatus->id,
                    'name' => $disabledStatus->name,
                ],
            ]
        ]);
    }
}
