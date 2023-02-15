<?php

namespace Tests\Feature\Api\Product\ProductStock;

use Tests\Feature\Api\ApiTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DestroyProductProductStockTest extends ApiTestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function DeleteProductStocks()
    {
    }
}
