<?php

namespace Tests\Feature\Api\Product\Resource;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Resource;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DestroyProductResourceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function testDeleteProductResource()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $product = Product::all()->random();
        $image = Resource::factory()
            ->isImage()
            ->productOwner($product)
            ->withOptions(['location' => 1])
            ->create();

        $response = $this->json('DELETE', route('api.v1.products.images.destroy', [
            $product,
            $image,
        ]));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'urls',
                    'type_resource',
                ]
            ])->assertJson([
                'data' => [
                    'id' => $image->id,
                    'urls' => $image->url,
                    'type_resource' => $image->type_resource,
                ]
            ]);
    }
}
