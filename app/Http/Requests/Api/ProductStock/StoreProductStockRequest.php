<?php

namespace App\Http\Requests\Api\ProductStock;

use App\Models\Product;
use Illuminate\Validation\Rule;
use App\Models\ProductAttributeOption;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ProductStock\ProductAttributeOpcionsOfStockRule;

class StoreProductStockRequest extends FormRequest
{
    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_id' => 'required|exists:products,id',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'price' => 'required|numeric|between:0.00,9999999999.99|regex:/^\d+(\.\d{1,2})?$/',
            'tax' => 'required|numeric|between:0.00,99.99|regex:/^\d+(\.\d{1,2})?$/',
            'product_attribute_options' => [
                Rule::requiredIf(function () {
                    $this->product = Product::find($this->product_id);

                    return $this->product && count($this->product->productAttributeOptions);
                }),
                'array',
                new ProductAttributeOpcionsOfStockRule($this->product)
                // function ($attribute, $value, $fail) {
                //     if ($this->product) {
                //         $this->product->load([
                //             'productAttributeOptions.productAttribute',
                //             'productStocks.productAttributeOptions',
                //         ]);

                //         $productAttributeOptions = $this->product->productAttributeOptions;

                //         $productAttribute = $productAttributeOptions
                //             ->pluck('productAttribute')
                //             ->unique('id')
                //             ->values();

                //         $countOfProductAttributes = count($productAttribute);

                //         if ($countOfProductAttributes !== count($value)) {
                //             return $fail('The number of ' . $attribute . ' must be equal to the product attributes associated with the product');
                //         }

                //         $productAttributeIds = ProductAttributeOption::whereIn('id', $value)
                //             ->get()
                //             ->pluck('product_attribute_id')
                //             ->toArray();

                //         if (
                //             count($productAttributeIds) !== count($value)
                //             || count($productAttributeIds) !== count(array_flip($productAttributeIds))
                //         ) {
                //             return $fail('The product attribute of ' . $attribute . ' must be different');
                //         }

                //         $productAttributeOptionIds = $productAttributeOptions->pluck('id');

                //         if (count(array_intersect($value, $productAttributeOptionIds->toArray())) !== count($value)) {
                //             return $fail('The ' . $attribute . ' must not be different to the product attribute options associated with the product');
                //         }

                //         $isCombinationCreated = false;
                //         $productAttributeOptionsFromStocks = $this->product->productStocks
                //             ->pluck('productAttributeOptions.*.id');

                //         $productAttributeOptionsFromStocks->map(
                //             function ($combination) use ($value, &$isCombinationCreated) {
                //                 if (count(array_intersect($value, $combination)) === count($value)) {
                //                     $isCombinationCreated = true;
                //                 }
                //             }
                //         );

                //         if ($isCombinationCreated) {
                //             return $fail('This combination of ' . $attribute . ' has already been created');
                //         }
                //     }
                // },
            ],
            'product_attribute_options.*' => 'required|exists:product_attribute_options,id',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'price.regex' => 'The price format must be between 0.00 and 9999999999.99',
            'tax.regex' => 'The tax format must be between 0.00 and 99.99',
        ];
    }
}
