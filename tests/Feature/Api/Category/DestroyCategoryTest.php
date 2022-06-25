<?php

namespace Tests\Feature\Api\Category;

use Tests\TestCase;
use App\Models\User;
use App\Models\Status;
use App\Models\Category;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DestroyCategoryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function testDeleteCategory()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $category = Category::all()->random();
        $disabledStatus = Status::disabled()->first();

        $response = $this->json('DELETE', route('api.v1.categories.destroy', [
            $category,
            'include' => 'status'
        ]));

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'slug',
            ]
        ])->assertJson([
            'data' => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'status' => [
                    'id' => $disabledStatus->id,
                    'name' => $disabledStatus->name,
                ],
            ]
        ]);
    }
}
