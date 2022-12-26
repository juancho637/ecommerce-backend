<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductStock;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResourceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $path = md5(random_int(1, 10000000) . microtime()) . '.jpg';

        return [
            'url' => ['original' => env('APP_URL') . '/storage/' . $path],
            'path' => ['original' => $path],
        ];
    }

    public function isImage()
    {
        return $this->state(function (array $attributes) {
            $name = md5(random_int(1, 10000000) . microtime());
            $url = env('APP_URL') . '/storage/' . $name;

            return [
                'url' => [
                    'original' => $url . '-original' . '.jpg',
                    'thumb' => $url . '-thumb' . '.jpg',
                    'small' => $url . '-small' . '.jpg',
                    'medium' => $url . '-medium' . '.jpg',
                ],
                'path' => [
                    'original' => $name . '-original' . '.jpg',
                    'thumb' => $name . '-thumb' . '.jpg',
                    'small' => $name . '-small' . '.jpg',
                    'medium' => $name . '-medium' . '.jpg',
                ],
            ];
        });
    }

    public function productOwner($productId = null)
    {
        return $this->state(function (array $attributes) use ($productId) {
            return [
                'obtainable_type' => Product::class,
                'obtainable_id' => $productId ?? Product::all()->random()->id,
                'type_resource' => Product::PRODUCT_IMAGE,
            ];
        });
    }

    public function productStockOwner($productStockId = null)
    {
        return $this->state(function (array $attributes) use ($productStockId) {
            return [
                'obtainable_type' => ProductStock::class,
                'obtainable_id' => $productStockId ?? ProductStock::all()->random()->id,
                'type_resource' => ProductStock::PRODUCT_STOCK_IMAGE,
            ];
        });
    }

    public function categoryOwner($categoryId = null)
    {
        return $this->state(function (array $attributes) use ($categoryId) {
            return [
                'obtainable_type' => Category::class,
                'obtainable_id' => $categoryId ?? Category::all()->random()->id,
                'type_resource' => Category::CATEGORY_IMAGE,
            ];
        });
    }

    public function withOptions($options = [])
    {
        return $this->state(function (array $attributes) use ($options) {
            return [
                'options' => $options,
            ];
        });
    }
}
