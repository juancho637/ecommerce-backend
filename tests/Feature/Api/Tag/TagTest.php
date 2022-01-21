<?php

namespace Tests\Feature\Api\Tag;

use App\Models\Tag;
use App\Models\User;
use App\Models\Status;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Api\ApiTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TagTest extends ApiTestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function testGetAllTags()
    {
        $response = $this->json('GET', route('api.v1.tags.index'));

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'name',
                    'slug',
                ]
            ]
        ]);
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

    public function testGetOneTag()
    {
        $tag = Tag::all()->random();

        $response = $this->json('GET', route('api.v1.tags.show', [$tag]));

        $response->assertStatus(200)->assertJson([
            'data' => [
                'id' => $tag->id,
                'name' => $tag->name,
            ]
        ]);
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

    public function testDeleteTag()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $tag = Tag::all()->random();
        $disabledStatus = Status::disabled()->first();

        $response = $this->json('DELETE', route('api.v1.tags.destroy', [
            $tag,
            'include' => 'status'
        ]));

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                'id',
                'name',
            ]
        ])->assertJson([
            'data' => [
                'id' => $tag->id,
                'name' => $tag->name,
                'slug' => $tag->slug,
                'status' => [
                    'id' => $disabledStatus->id,
                    'name' => $disabledStatus->name,
                ],
            ]
        ]);
    }
}
