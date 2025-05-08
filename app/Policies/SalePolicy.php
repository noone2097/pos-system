<?php

namespace App\Policies;

use App\Models\Sale;
use App\Models\User;

class SalePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'cashier']);
    }

    public function view(User $user, Sale $sale): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        // Cashiers can only view sales they created
        if ($user->hasRole('cashier')) {
            return $sale->user_id === $user->id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'cashier']);
    }

    public function update(User $user, Sale $sale): bool
    {
        // Sales cannot be updated once created
        return false;
    }

    public function delete(User $user, Sale $sale): bool
    {
        // Only admin can delete sales
        return $user->hasRole('admin');
    }

    public function void(User $user, Sale $sale): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        // Cashiers can void their own sales within 24 hours
        if ($user->hasRole('cashier')) {
            return $sale->user_id === $user->id &&
                   $sale->created_at->isAfter(now()->subHours(24));
        }

        return false;
    }

    public function print(User $user, Sale $sale): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        // Cashiers can only print sales they created
        if ($user->hasRole('cashier')) {
            return $sale->user_id === $user->id;
        }

        return false;
    }
}
