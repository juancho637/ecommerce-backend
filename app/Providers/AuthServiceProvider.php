<?php

namespace App\Providers;

use App\Models\City;
use App\Models\User;
use App\Models\State;
use App\Models\Country;
use App\Policies\CityPolicy;
use App\Policies\UserPolicy;
use App\Policies\StatePolicy;
use App\Policies\CountryPolicy;
use Illuminate\Support\Facades\Gate;
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
