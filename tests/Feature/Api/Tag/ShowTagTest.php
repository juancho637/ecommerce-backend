<?php

namespace Tests\Feature\Api\Tag;

use App\Models\Tag;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowTagTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
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
}
