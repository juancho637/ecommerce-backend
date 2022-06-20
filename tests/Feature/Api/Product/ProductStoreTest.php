<?php

namespace Tests\Feature\Api\Product;

use App\Models\Tag;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use Laravel\Sanctum\Sanctum;
use Illuminate\Http\UploadedFile;
use App\Models\ProductAttributeOption;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductStoreTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->seed();
    }

    public function testCreateProductWithAttributes()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $name = $this->faker->unique()->sentence(1, false);
        $shortDescription = $this->faker->sentence(30);
        $description = $this->faker->paragraphs(3, true);
        $category = Category::all()->random();
        $tags = Tag::all()->random(mt_rand(2, 5))->pluck('id');
        $productAttributeOptions = ProductAttributeOption::all()->random(3)->pluck('id');

        $response = $this->json('POST', route('api.v1.products.store', [
            'include' => 'product_attribute_options'
        ]), [
            'category_id' => $category->id,
            'name' => $name,
            'short_description' => $shortDescription,
            'description' => $description,
            'tags' => $tags,
            'product_attribute_options' => $productAttributeOptions,
            'photos' => [
                [
                    'file' => UploadedFile::fake()->image('image.jpg'),
                    'location' => 1,
                ]
            ],
        ]);

        $response->assertStatus(201)->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'slug',
                'short_description',
                'description',
            ]
        ])->assertJson([
            'data' => [
                'name' => $name,
                'short_description' => $shortDescription,
                'description' => $description,
            ]
        ]);
    }

    public function testCreateProductWithoutAttributes()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $name = $this->faker->unique()->sentence(1, false);
        $shortDescription = $this->faker->sentence(30);
        $description = $this->faker->paragraphs(3, true);
        $category = Category::all()->random();
        $tags = Tag::all()->random(mt_rand(2, 5))->pluck('id');

        $response = $this->json('POST', route('api.v1.products.store'), [
            'category_id' => $category->id,
            'name' => $name,
            'short_description' => $shortDescription,
            'description' => $description,
            'tags' => $tags,
            'photos' => [
                [
                    'file' => UploadedFile::fake()->image('image.jpg'),
                    'location' => 1,
                ]
            ],
        ]);

        $response->assertStatus(201)->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'slug',
                'short_description',
                'description',
            ]
        ])->assertJson([
            'data' => [
                'name' => $name,
                'short_description' => $shortDescription,
                'description' => $description,
            ]
        ]);
    }
}
