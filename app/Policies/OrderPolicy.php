<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OrderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Order $order): Response
    {
        return $user->is_admin || $order->user_id === $user->id
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    public function addAddress(User $user, Order $order): Response
    {
        return $order->user_id === $user->id
            && in_array($order->status, [Order::STATUS_AWAITING_PAYMENT, 'pending'], true)
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function pay(User $user, Order $order): Response
    {
        return $order->user_id === $user->id
            && $order->address_id !== null
            && $order->status === Order::STATUS_AWAITING_PAYMENT
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function delete(User $user, Order $order): bool
    {
        return $order->user_id === $user->id
            && in_array($order->status, [Order::STATUS_AWAITING_PAYMENT, 'pending'], true);
    }

    public function updateStatus(User $user, Order $order): bool
    {
        return $user->is_admin;
    }
}
