<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;

class CustomerPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'cashier']);
    }

    public function view(User $user, Customer $customer): bool
    {
        return $user->hasAnyRole(['admin', 'cashier']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'cashier']);
    }

    public function update(User $user, Customer $customer): bool
    {
        // Only admin can update existing customers
        return $user->hasRole('admin');
    }

    public function delete(User $user, Customer $customer): bool
    {
        // Only admin can delete customers
        return $user->hasRole('admin');
    }

    // Allow both roles to select customers in forms
    public function viewOptions(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'cashier']);
    }
}
