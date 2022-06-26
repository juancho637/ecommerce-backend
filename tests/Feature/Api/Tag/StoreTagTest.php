<?php

namespace Tests\Feature\Api\Tag;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreTagTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function testCreateTag()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $name = $this->faker->unique()->sentence(1, false);

        $response = $this->json('POST', route('api.v1.tags.store'), [
            'name' => $name,
        ]);

        $response->assertStatus(201)->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'slug',
            ]
        ])->assertJson([
            'data' => [
                'name' => $name,
            ]
        ]);
    }
}
