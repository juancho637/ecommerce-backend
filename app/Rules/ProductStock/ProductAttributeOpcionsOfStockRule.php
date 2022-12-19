<?php

namespace App\Rules\ProductStock;

use App\Models\Product;
use App\Models\ProductAttributeOption;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;

class ProductAttributeOpcionsOfStockRule implements InvokableRule
{
    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        if ($this->product) {
            $this->product->load([
                'productAttributeOptions.productAttribute',
                'productStocks.productAttributeOptions',
            ]);

            $productAttributeOptions = $this->product->productAttributeOptions;

            $productAttribute = $productAttributeOptions
                ->pluck('productAttribute')
                ->unique('id')
                ->values();

            $countOfProductAttributes = count($productAttribute);

            if ($countOfProductAttributes !== count($value)) {
                return $fail('The number of ' . $attribute . ' must be equal to the product attributes associated with the product');
            }

            $productAttributeIds = ProductAttributeOption::whereIn('id', $value)
                ->get()
                ->pluck('product_attribute_id')
                ->toArray();

            if (
                count($productAttributeIds) !== count($value)
                || count($productAttributeIds) !== count(array_flip($productAttributeIds))
            ) {
                return $fail('The product attribute of ' . $attribute . ' must be different');
            }

            $productAttributeOptionIds = $productAttributeOptions->pluck('id');

            if (count(array_intersect($value, $productAttributeOptionIds->toArray())) !== count($value)) {
                return $fail('The ' . $attribute . ' must not be different to the product attribute options associated with the product');
            }

            $isCombinationCreated = false;
            $productAttributeOptionsFromStocks = $this->product->productStocks
                ->pluck('productAttributeOptions.*.id');

            $productAttributeOptionsFromStocks->map(
                function ($combination) use ($value, &$isCombinationCreated) {
                    if (count(array_intersect($value, $combination)) === count($value)) {
                        $isCombinationCreated = true;
                    }
                }
            );

            if ($isCombinationCreated) {
                return $fail('This combination of ' . $attribute . ' has already been created');
            }
        }
    }
}
