<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'customer_id',
        'user_id',
        'payment_method',
        'payment_amount',
        'subtotal',
        'tax',
        'discount',
        'total',
        'change',
        'status',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public static function generateReference(): string
    {
        $latestSale = self::latest()->first();
        $lastNumber = $latestSale ? intval(substr($latestSale->reference, 4)) : 0;
        $nextNumber = str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
        
        return 'INV-' . $nextNumber;
    }
}