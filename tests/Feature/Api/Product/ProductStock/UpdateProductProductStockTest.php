<?php

namespace Tests\Feature\Api\Product\ProductStock;

use App\Models\User;
use App\Models\Product;
use App\Models\ProductStock;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Api\ApiTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateProductProductStockTest extends ApiTestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function testUpdateProductStock()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $product = Product::all()->random();
        $productStock = ProductStock::factory()->create([
            'product_id' => $product->id,
        ]);
        // $name = $this->faker->unique()->sentence(1, false);
        // $shortDescription = $this->faker->sentence(30);
        // $description = $this->faker->paragraphs(3, true);
        // $productTags = $product->tags()->pluck('id');
        // $tags = Tag::whereNotIn('id', $productTags)->get()->random(1)->pluck('id');
        // $productTags[] = $tags[0];

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
}
