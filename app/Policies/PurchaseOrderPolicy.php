<?php

namespace App\Policies;

use App\Models\User;
use App\Models\PurchaseOrder;
use Illuminate\Auth\Access\HandlesAuthorization;

class PurchaseOrderPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view purchase orders');
    }

    public function view(User $user, PurchaseOrder $purchaseOrder): bool
    {
        return $user->can('view purchase orders');
    }

    public function create(User $user): bool
    {
        return $user->can('create purchase orders');
    }

    public function update(User $user, PurchaseOrder $purchaseOrder): bool
    {
        if (!$user->can('edit purchase orders')) {
            return false;
        }

        return $purchaseOrder->canBeEdited();
    }

    public function receive(User $user, PurchaseOrder $purchaseOrder): bool
    {
        if (!$user->can('receive purchase orders')) {
            return false;
        }

        return $purchaseOrder->canReceiveItems();
    }

    public function delete(User $user, PurchaseOrder $purchaseOrder): bool
    {
        if (!$user->can('edit purchase orders')) {
            return false;
        }

        return $purchaseOrder->status === 'draft';
    }
}