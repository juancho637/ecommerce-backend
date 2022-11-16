<?php

namespace App\Providers;

use App\Models\Tag;
use App\Models\City;
use App\Models\User;
use App\Models\State;
use App\Models\Country;
use App\Models\Product;
use App\Models\Category;
use App\Policies\TagPolicy;
use App\Policies\CityPolicy;
use App\Models\ProductStock;
use App\Policies\UserPolicy;
use App\Policies\StatePolicy;
use App\Policies\CountryPolicy;
use App\Policies\ProductPolicy;
use App\Policies\CategoryPolicy;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\Gate;
use App\Models\ProductSpecification;
use App\Policies\ProductStockPolicy;
use App\Models\ProductAttributeOption;
use App\Policies\ProductAttributePolicy;
use App\Policies\ProductSpecificationPolicy;
use App\Policies\ProductAttributeOptionPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Country::class => CountryPolicy::class,
        State::class => StatePolicy::class,
        City::class => CityPolicy::class,
        User::class => UserPolicy::class,
        Category::class => CategoryPolicy::class,
        Tag::class => TagPolicy::class,
        ProductAttribute::class => ProductAttributePolicy::class,
        ProductAttributeOption::class => ProductAttributeOptionPolicy::class,
        Product::class => ProductPolicy::class,
        ProductSpecification::class => ProductSpecificationPolicy::class,
        ProductStock::class => ProductStockPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
