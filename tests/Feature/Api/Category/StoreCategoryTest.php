<?php

namespace Tests\Feature\Api\Category;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Resource;
use Laravel\Sanctum\Sanctum;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreCategoryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function testCreateCategory()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $image = Resource::factory()->isImage()->create();
        $name = $this->faker->unique()->sentence(1, false);
        $parent = $this->faker->boolean() ? Category::whereNull('parent_id')->get()->random()->id : null;

        $response = $this->json('POST', route('api.v1.categories.store', [
            'include' => 'image'
        ]), [
            'name' => $name,
            'image' => $image->id,
            'parent_id' => $parent,
        ]);

        $response->assertStatus(Response::HTTP_CREATED)->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'slug',
                'image' => [
                    'id',
                    'owner_id',
                    'type_resource',
                    'urls' => [
                        'original',
                        'thumb',
                        'small',
                        'medium',
                    ],
                ],
            ]
        ])->assertJson([
            'data' => [
                'name' => $name,
            ]
        ]);
    }
}
