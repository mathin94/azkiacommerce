<?php

namespace App\Policies\Shop;

use App\Models\User;
use App\Models\Shop\Voucher;
use Illuminate\Auth\Access\HandlesAuthorization;

class VoucherPolicy
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
        return $user->can('view_any_shop::voucher');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Shop\Voucher  $voucher
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Voucher $voucher)
    {
        return $user->can('view_shop::voucher');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can('create_shop::voucher');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Shop\Voucher  $voucher
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Voucher $voucher)
    {
        return $user->can('update_shop::voucher');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Shop\Voucher  $voucher
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Voucher $voucher)
    {
        return $user->can('delete_shop::voucher');
    }

    /**
     * Determine whether the user can bulk delete.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function deleteAny(User $user)
    {
        return $user->can('delete_any_shop::voucher');
    }

    /**
     * Determine whether the user can permanently delete.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Shop\Voucher  $voucher
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Voucher $voucher)
    {
        return $user->can('{{ ForceDelete }}');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDeleteAny(User $user)
    {
        return $user->can('{{ ForceDeleteAny }}');
    }

    /**
     * Determine whether the user can restore.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Shop\Voucher  $voucher
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Voucher $voucher)
    {
        return $user->can('{{ Restore }}');
    }

    /**
     * Determine whether the user can bulk restore.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restoreAny(User $user)
    {
        return $user->can('{{ RestoreAny }}');
    }

    /**
     * Determine whether the user can replicate.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Shop\Voucher  $voucher
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function replicate(User $user, Voucher $voucher)
    {
        return $user->can('{{ Replicate }}');
    }

    /**
     * Determine whether the user can reorder.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function reorder(User $user)
    {
        return $user->can('{{ Reorder }}');
    }

}
