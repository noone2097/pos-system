<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseOrderItem extends Model
{
    protected $fillable = [
        'purchase_order_id',
        'product_id',
        'quantity',
        'unit_price',
        'received_quantity',
        'subtotal',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'quantity' => 'integer',
        'received_quantity' => 'integer',
    ];

    protected static function booted(): void
    {
        static::created(function ($item) {
            $item->purchaseOrder->recalculateTotal();
        });

        static::updated(function ($item) {
            $item->purchaseOrder->recalculateTotal();
        });

        static::deleted(function ($item) {
            $item->purchaseOrder->recalculateTotal();
        });

        // Calculate subtotal before saving
        static::saving(function ($item) {
            $item->subtotal = $item->quantity * $item->unit_price;
        });
    }

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}