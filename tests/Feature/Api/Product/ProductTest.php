<?php

namespace Tests\Feature\Api\Product;

use App\Models\Tag;
use Tests\TestCase;
use App\Models\User;
use App\Models\Status;
use App\Models\Product;
use App\Models\Category;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function testGetAllProducts()
    {
        $response = $this->json('GET', route('api.v1.products.index'));

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'name',
                    'slug',
                    'short_description',
                    'description',
                ]
            ]
        ]);
    }


    public function testCreateProduct()
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

    public function testGetOneProduct()
    {
        $product = Product::all()->random();

        $response = $this->json('GET', route('api.v1.products.show', [$product]));

        $response->assertStatus(200)->assertJson([
            'data' => [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'short_description' => $product->short_description,
                'description' => $product->description,
            ]
        ]);
    }

    public function testUpdateProduct()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $product = Product::all()->random();
        $name = $this->faker->unique()->sentence(1, false);
        $shortDescription = $this->faker->sentence(30);
        $description = $this->faker->paragraphs(3, true);
        $productTags = $product->tags()->pluck('id');
        $tags = Tag::whereNotIn('id', $productTags)->get()->random(1)->pluck('id');
        $productTags[] = $tags[0];

        $response = $this->json('PUT', route('api.v1.products.update', [$product]), [
            'name' => $name,
            'short_description' => $shortDescription,
            'description' => $description,
            'tags' => $productTags,
        ]);

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'slug',
                'short_description',
                'description',
            ]
        ])->assertJson([
            'data' => [
                'id' => $product->id,
                'name' => $name,
                'short_description' => $shortDescription,
                'description' => $description,
            ]
        ]);
    }

    public function testDeleteProduct()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $product = Product::all()->random();
        $disabledStatus = Status::disabled()->first();

        $response = $this->json('DELETE', route('api.v1.products.destroy', [
            $product,
            'include' => 'status'
        ]));

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'slug',
                'short_description',
                'description',
            ]
        ])->assertJson([
            'data' => [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'short_description' => $product->short_description,
                'description' => $product->description,
                'status' => [
                    'id' => $disabledStatus->id,
                    'name' => $disabledStatus->name,
                ],
            ]
        ]);
    }
}
