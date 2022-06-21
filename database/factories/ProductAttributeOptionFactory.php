<?php

namespace Database\Factories;

use App\Models\Status;
use App\Models\ProductAttribute;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductAttributeOptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $productAttribute = ProductAttribute::all()->random();

        return [
            'product_attribute_id' => $productAttribute->id,
            'name' => $this->getName($productAttribute),
            'option' => $this->getOption($productAttribute),
            'status_id' => Status::enabled()->value('id'),
        ];
    }

    public function productAttribute($productAttribute)
    {
        return $this->state(function (array $attributes) use ($productAttribute) {
            return [
                'product_attribute_id' => $productAttribute,
                'name' => $this->getName($productAttribute),
                'option' => $this->getOption($productAttribute),
            ];
        });
    }

    private function getName($productAttribute)
    {
        return ($productAttribute->type === ProductAttribute::COLOR_TYPE)
            ? $this->faker->colorName()
            : $this->faker->unique()->sentence(1, false);
    }

    private function getOption($productAttribute)
    {
        return ($productAttribute->type === ProductAttribute::COLOR_TYPE)
            ? $this->faker->hexColor()
            : null;
    }
}
