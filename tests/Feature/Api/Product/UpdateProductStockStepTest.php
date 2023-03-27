<?php

namespace Tests\Feature\Api\Product;

use App\Models\Tag;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Resource;
use Laravel\Sanctum\Sanctum;
use App\Models\ProductAttributeOption;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateProductStockStepTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function testUpdateProductStepWithAttributes()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $product = Product::all()->random();
        $oldImage = Resource::factory()
            ->isImage()
            ->productOwner($product)
            ->withOptions(['location' => 1])
            ->create();

        $name = $this->faker->unique()->sentence(1, false);
        $shortDescription = $this->faker->sentence(30);
        $description = $this->faker->paragraphs(3, true);
        $image = Resource::factory()->isImage()->create();

        $productAttributeOptionsToAdd = ProductAttributeOption::all()->random(3)->pluck('id');
        $productAttributeOptions['attach'] = $productAttributeOptionsToAdd;

        $productTags = $product->tags();
        $tagsToAdd = Tag::whereNotIn('id', $productTags->pluck('id'))
            ->get()
            ->random(1)
            ->pluck('id');
        $tags['detach'] = $productTags->get()->random(1)->pluck('id');
        $tags['attach'] = $tagsToAdd;

        $response = $this->json('PUT', route('api.v1.products_general.update', [
            $product,
            'include' => 'images'
        ]), [
            'name' => $name,
            'short_description' => $shortDescription,
            'description' => $description,
            'tags' => $tags,
            'product_attribute_options' => $productAttributeOptions,
            'images' => [
                'attach' => [
                    [
                        'id' => $image->id,
                        'location' => 1,
                    ]
                ],
                'detach' => [
                    $oldImage->id
                ]
            ]
        ]);

        // dd($response->decodeResponseJson());

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

    public function testUpdateProductStepWithoutAttributes()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $product = Product::all()->random();
        $name = $this->faker->unique()->sentence(1, false);
        $shortDescription = $this->faker->sentence(30);
        $description = $this->faker->paragraphs(3, true);

        $productTags = $product->tags();
        $tagsToAdd = Tag::whereNotIn('id', $productTags->pluck('id'))
            ->get()
            ->random(1)
            ->pluck('id');
        $tags['attach'] = $tagsToAdd;

        $response = $this->json('PUT', route('api.v1.products_general.update', [$product]), [
            'name' => $name,
            'short_description' => $shortDescription,
            'description' => $description,
            'tags' => $tags,
        ]);

        // dd($response->decodeResponseJson());

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
