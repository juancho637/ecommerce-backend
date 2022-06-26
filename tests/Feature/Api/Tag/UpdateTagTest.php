<?php

namespace Tests\Feature\Api\Tag;

use App\Models\Tag;
use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateTagTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function testUpdateTag()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $tag = Tag::all()->random();
        $name = $this->faker->unique()->sentence(1, false);

        $response = $this->json('PUT', route('api.v1.tags.update', [$tag]), [
            'name' => $name,
        ]);

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'slug',
            ]
        ])->assertJson([
            'data' => [
                'id' => $tag->id,
                'name' => $name,
            ]
        ]);
    }
}
