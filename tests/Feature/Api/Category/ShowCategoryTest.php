<?php

namespace Tests\Feature\Api\Category;

use Tests\TestCase;
use App\Models\Category;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function testGetOneCategory()
    {
        $category = Category::all()->random();

        $response = $this->json('GET', route('api.v1.categories.show', [$category]));

        $response->assertStatus(200)->assertJson([
            'data' => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
            ]
        ]);
    }
}
