<?php

namespace Tests\Feature\Api\Category;

use App\Models\User;
use App\Models\Status;
use App\Models\Category;
use Laravel\Sanctum\Sanctum;
use Illuminate\Http\UploadedFile;
use Tests\Feature\Api\ApiTestCase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends ApiTestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->seed();
    }

    public function testGetAllCategories()
    {
        $response = $this->json('GET', route('api.v1.categories.index'));

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

    public function testGetOneCategory()
    {
        $category = Category::all()->random();

        $response = $this->json('GET', route('api.v1.categories.show', [$category]));

        $response->assertStatus(200)->assertJson([
            'data' => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
            ]
        ]);
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

    public function testDeleteCategory()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $category = Category::all()->random();
        $disabledStatus = Status::disabled()->first();

        $response = $this->json('DELETE', route('api.v1.categories.destroy', [
            $category,
            'include' => 'status'
        ]));

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'slug',
            ]
        ])->assertJson([
            'data' => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'status' => [
                    'id' => $disabledStatus->id,
                    'name' => $disabledStatus->name,
                ],
            ]
        ]);
    }
}
