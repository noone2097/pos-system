<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    private function generateUniqueBarcode(): string
    {
        do {
            // Generate a 12-digit number (for EAN-13, last digit is check digit)
            $number = '';
            for ($i = 0; $i < 12; $i++) {
                $number .= mt_rand(0, 9);
            }

            // Calculate EAN-13 check digit
            $sum = 0;
            for ($i = 0; $i < 12; $i++) {
                $sum += $number[$i] * ($i % 2 ? 3 : 1);
            }
            $checkDigit = (10 - ($sum % 10)) % 10;
            $barcode = $number . $checkDigit;

            $exists = Product::where('barcode', $barcode)->exists();
        } while ($exists);

        return $barcode;
    }

    public function run(): void
    {
        // Create Categories
        $categories = [
            'Beverages' => [
                'Soft Drinks',
                'Coffee',
                'Tea',
                'Energy Drinks'
            ],
            'Snacks' => [
                'Chips',
                'Cookies',
                'Candies',
                'Chocolates'
            ],
            'Groceries' => [
                'Canned Goods',
                'Instant Noodles',
                'Rice',
                'Condiments'
            ],
            'Personal Care' => [
                'Soap',
                'Shampoo',
                'Toothpaste',
                'Deodorant'
            ]
        ];

        foreach ($categories as $mainCategory => $subCategories) {
            foreach ($subCategories as $subCategory) {
                $category = Category::create([
                    'name' => $subCategory,
                    'slug' => Str::slug($subCategory),
                    'description' => "Various {$subCategory} products"
                ]);

                // Create 5 products for each category
                for ($i = 1; $i <= 5; $i++) {
                    $productName = "{$subCategory} Item {$i}";
                    Product::create([
                        'name' => $productName,
                        'description' => "Description for {$subCategory} Item {$i}",
                        'barcode' => $this->generateUniqueBarcode(),
                        'price' => rand(10, 1000),
                        'stock' => rand(10, 100),
                        'category_id' => $category->id,
                        'is_active' => true,
                    ]);
                }
            }
        }
    }
}