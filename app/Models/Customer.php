<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
    ];

    public static function generateUniqueName(): string
    {
        do {
            $number = mt_rand(100000, 999999);
            $name = "Customer " . $number;
            $exists = self::where('name', $name)->exists();
        } while ($exists);

        return $name;
    }

    public static function createDefault(): self
    {
        return self::create([
            'name' => self::generateUniqueName(),
            'email' => null,
            'phone' => null,
            'address' => null,
        ]);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }
}