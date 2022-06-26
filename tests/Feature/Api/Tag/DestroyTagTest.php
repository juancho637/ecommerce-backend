<?php

namespace Tests\Feature\Api\Tag;

use App\Models\Tag;
use Tests\TestCase;
use App\Models\User;
use App\Models\Status;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DestroyTagTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
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
