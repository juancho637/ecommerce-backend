<?php

namespace Tests\Feature\Api\Category;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use Laravel\Sanctum\Sanctum;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreCategoryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->seed();
    }

    public function testCreateCategory()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $name = $this->faker->unique()->sentence(1, false);
        $parent = $this->faker->boolean() ? Category::whereNull('parent_id')->get()->random()->id : null;

        $response = $this->json('POST', route('api.v1.categories.store'), [
            'name' => $name,
            'image' => UploadedFile::fake()->image('avatar.jpg'),
            'parent_id' => $parent,
        ]);

        $response->assertStatus(201)->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'slug',
                'image' => [
                    'id',
                    'url',
                ],
            ]
        ])->assertJson([
            'data' => [
                'name' => $name,
            ]
        ]);
    }
}
