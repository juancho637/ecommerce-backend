<?php

namespace Tests\Feature\Api\Category;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateCategoryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function testUpdateCategory()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $category = Category::all()->random();
        $name = $this->faker->unique()->sentence(1, false);

        $response = $this->json('PUT', route('api.v1.categories.update', [$category]), [
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
                'id' => $category->id,
                'name' => $name,
            ]
        ]);
    }
}
