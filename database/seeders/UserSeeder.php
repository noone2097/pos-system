<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('admin');

        // Create Cashier
        $cashier = User::create([
            'name' => 'Cashier User',
            'email' => 'cashier@example.com',
            'password' => Hash::make('password'),
        ]);
        $cashier->assignRole('cashier');
    }
}
