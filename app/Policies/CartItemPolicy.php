<?php

namespace App\Policies;

use App\Models\CartItem;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CartItemPolicy
{
    public function update(User $user, CartItem $cartItem): Response
    {
        return $cartItem->cart()->where('user_id', $user->id)->exists()
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CartItem $cartItem): Response
    {
        return $cartItem->cart()->where('user_id', $user->id)->exists()
            ? Response::allow()
            : Response::denyAsNotFound();
    }
}
