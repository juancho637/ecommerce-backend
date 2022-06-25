<?php

namespace Tests\Feature\Api\Category;

use Tests\TestCase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndexCategoryTest extends TestCase
{
    use RefreshDatabase;

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
}
