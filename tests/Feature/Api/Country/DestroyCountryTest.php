<?php

namespace Tests\Feature\Api\Country;

use Tests\TestCase;
use App\Models\User;
use App\Models\Status;
use App\Models\Country;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DestroyCountryTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
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
