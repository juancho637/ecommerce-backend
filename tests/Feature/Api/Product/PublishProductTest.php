<?php

namespace Tests\Feature\Api\Product;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Status;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PublishProductTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->seed();
    }

    public function testPublishProductOfTypeProductNotVariable()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $enabledStatus = Status::enabled()->first();
        $product = Product::factory()
            ->typeProduct()
            ->statusGeneralStep()
            ->withTags(5)
            ->create();

        $response = $this->json('POST', route('api.v1.products.publish', [$product, 'include' => 'status']));

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
                'name' => $product->name,
                'slug' => $product->slug,
                'short_description' => $product->short_description,
                'description' => $product->description,
                'status' => [
                    'id' => $enabledStatus->id,
                    'name' => $enabledStatus->name,
                ],
            ]
        ]);
    }
}
