<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ProductStock;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductStockPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProductStock  $productStock
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, ProductStock $productStock)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProductStock  $productStock
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, ProductStock $productStock)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProductStock  $productStock
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, ProductStock $productStock)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProductStock  $productStock
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, ProductStock $productStock)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProductStock  $productStock
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, ProductStock $productStock)
    {
        //
    }
}
