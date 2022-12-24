<?php

namespace Tests\Feature\Api\Resource;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreResourceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->seed();
    }

    public function testUploadImageResource()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $response = $this->json('POST', route('api.v1.resources.store'), [
            'file' => UploadedFile::fake()->image('image.jpg'),
        ]);

        $response->assertStatus(201)->assertJsonStructure([
            'data' => [
                'id',
                'urls' => [
                    'original',
                    'thumb',
                    'small',
                    'medium',
                ],
            ]
        ]);
    }

    public function testUploadFileResource()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $response = $this->json('POST', route('api.v1.resources.store'), [
            'file' => UploadedFile::fake()->create('file.csv'),
        ]);

        $response->assertStatus(201)->assertJsonStructure([
            'data' => [
                'id',
                'urls' => [
                    'original',
                ],
            ]
        ]);
    }
}
