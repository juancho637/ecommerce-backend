<?php

namespace Tests\Feature\Api\Product\Resource;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Laravel\Sanctum\Sanctum;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Actions\Product\UpdateOrCreateProductPhotos;

class ProductResourceDestroyTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->seed();
    }

    public function testDeleteProductResource()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $product = Product::all()->random();
        $photo = app(UpdateOrCreateProductPhotos::class)($product, [
            [
                'file' => UploadedFile::fake()->image('image.jpg'),
                'location' => 1
            ]
        ])[0];

        $response = $this->json('DELETE', route('api.v1.products.photos.destroy', [
            $product,
            $photo,
        ]));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'url',
                    'type_resource',
                ]
            ])->assertJson([
                'data' => [
                    'id' => $photo->id,
                    'url' => $photo->url,
                    'type_resource' => $photo->type_resource,
                ]
            ]);
    }
}
