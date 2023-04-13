<?php

namespace Tests\Feature\Api\Product;

use App\Models\Tag;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Resource;
use Laravel\Sanctum\Sanctum;
use App\Models\ProductAttributeOption;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreProductTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function testCreateProductWithAttributes()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $image = Resource::factory()->isImage()->create();
        $type = Product::PRODUCT_TYPE;
        $name = $this->faker->unique()->sentence(1, false);
        $price = 100000.12;
        $tax = 19;
        $isVariable = true;
        $shortDescription = $this->faker->sentence(30);
        $description = $this->faker->paragraphs(3, true);
        $category = Category::all()->random();
        $tags = Tag::all()->random(mt_rand(2, 5))->pluck('id');
        $productAttributeOptions = ProductAttributeOption::all()->random(3)->pluck('id');

        $data = [
            'category_id' => $category->id,
            'type' => $type,
            'name' => $name,
            'price' => $price,
            'tax' => $tax,
            'is_variable' => $isVariable,
            'short_description' => $shortDescription,
            'description' => $description,
            'tags' => ['attach' => $tags],
            'product_attribute_options' => ['attach' => $productAttributeOptions],
            'images' => [
                'attach' => [
                    [
                        'id' => $image->id,
                        'location' => 1,
                    ]
                ]
            ]
        ];

        $response = $this->json('POST', route('api.v1.products_general.store'), $data);

        $response->assertStatus(201)->assertJsonStructure([
            'data' => [
                'id',
                'type',
                'name',
                'slug',
                'price',
                'tax',
                'short_description',
                'description',
                'is_variable',
            ]
        ])->assertJson([
            'data' => [
                'type' => $type,
                'name' => $name,
                'price' => $price,
                'min_price' => $price,
                'max_price' => $price,
                'tax' => $tax,
                'short_description' => $shortDescription,
                'description' => $description,
                'is_variable' => $isVariable,
            ]
        ]);
    }

    public function testCreateProductWithoutAttributes()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $image = Resource::factory()->isImage()->create();
        $type = Product::PRODUCT_TYPE;
        $name = $this->faker->unique()->sentence(1, false);
        $price = 100000.12;
        $tax = 19;
        $isVariable = false;
        $shortDescription = $this->faker->sentence(30);
        $description = $this->faker->paragraphs(3, true);
        $category = Category::all()->random();
        $tags = Tag::all()->random(mt_rand(2, 5))->pluck('id');
        $stock = 10;
        $width = 10.1;
        $height = 10.1;
        $length = 10.1;
        $weight = 10.1;

        $response = $this->json('POST', route('api.v1.products_general.store', [
            'include' => 'product_stocks'
        ]), [
            'category_id' => $category->id,
            'type' => $type,
            'name' => $name,
            'price' => $price,
            'tax' => $tax,
            'is_variable' => $isVariable,
            'short_description' => $shortDescription,
            'description' => $description,
            'tags' => ['attach' => $tags],
            'images' => [
                'attach' => [
                    [
                        'id' => $image->id,
                        'location' => 1,
                    ]
                ]
            ],
            'stock' => $stock,
            'width' => $width,
            'height' => $height,
            'length' => $length,
            'weight' => $weight,
        ]);

        $response->assertStatus(201)->assertJsonStructure([
            'data' => [
                'id',
                'type',
                'name',
                'slug',
                'price',
                'tax',
                'short_description',
                'description',
                'price',
                'tax',
                'product_stocks' => [
                    [
                        'stock',
                        'width',
                        'height',
                        'length',
                        'weight',
                    ]
                ]
            ]
        ])->assertJson([
            'data' => [
                'type' => $type,
                'name' => $name,
                'price' => $price,
                'min_price' => $price,
                'max_price' => $price,
                'tax' => $tax,
                'short_description' => $shortDescription,
                'description' => $description,
                'price' => $price,
                'product_stocks' => [
                    [
                        'stock' => $stock,
                        'width' => $width,
                        'height' => $height,
                        'length' => $length,
                        'weight' => $weight,
                    ]
                ]
            ]
        ]);
    }
}
