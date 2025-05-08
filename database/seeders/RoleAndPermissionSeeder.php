<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Product permissions
            'view products',
            'create products',
            'edit products',
            'delete products',

            // Category permissions
            'view categories',
            'create categories',
            'edit categories',
            'delete categories',

            // Customer permissions
            'view customers',
            'create customers',
            'edit customers',
            'delete customers',

            // Sale permissions
            'view sales',
            'create sales',
            'void sales',
            'view reports',

            // User management
            'view users',
            'create users',
            'edit users',
            'delete users',
        ];

        // Create or update permissions
        $createdPermissions = collect($permissions)->map(function ($permission) {
            return Permission::firstOrCreate(['name' => $permission]);
        });

        // Create or update roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions($createdPermissions);

        $cashierRole = Role::firstOrCreate(['name' => 'cashier']);
        $cashierRole->syncPermissions([
            'view products',
            'view categories',
            'view customers',
            'create customers',
            'edit customers',
            'view sales',
            'create sales',
        ]);
    }
}
