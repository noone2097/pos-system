<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'supplier_id',
        'po_number',
        'date',
        'status',
        'total_amount',
        'notes',
    ];

    protected $casts = [
        'date' => 'datetime',
        'total_amount' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        // Set initial status
        static::creating(function ($purchaseOrder) {
            $purchaseOrder->status = 'draft';
        });
    }

    public function recalculateTotal(): void
    {
        $this->total_amount = $this->items()->sum('subtotal');
        $this->save();
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function canBeEdited(): bool
    {
        return $this->status === 'draft';
    }

    public function canReceiveItems(): bool
    {
        return in_array($this->status, ['pending', 'partially_received']);
    }

    public function submit(): void
    {
        if ($this->status !== 'draft') {
            throw new \InvalidArgumentException('Only draft orders can be submitted');
        }

        if ($this->items->isEmpty()) {
            throw new \InvalidArgumentException('Cannot submit empty purchase order');
        }

        $this->status = 'pending';
        $this->save();
    }

    public static function generatePoNumber(): string
    {
        $lastPO = self::orderBy('id', 'desc')->first();
        $number = $lastPO ? intval(substr($lastPO->po_number, 2)) + 1 : 1;
        return 'PO' . str_pad($number, 8, '0', STR_PAD_LEFT);
    }
}