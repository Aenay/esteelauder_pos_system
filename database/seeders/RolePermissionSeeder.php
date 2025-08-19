<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions
        $permissions = [
            // Admin
            'manage-users',
            'assign-roles',
            'configure-settings',

            // Store Manager
            'manage-products',
            'manage-promotions',
            'manage-suppliers',
            'manage-staff',
            'view-reports',

            // Sales Assistant
            'create-orders',
            'process-payments',
            'manage-customers',
            'apply-promotions',
            'process-returns',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Define permissions for Sales Assistant
        $salesAssistantPermissions = [
            'create-orders',
            'process-payments',
            'manage-customers',
            'apply-promotions',
            'process-returns',
        ];

        // Create Sales Assistant role and assign permissions
        $salesAssistantRole = Role::firstOrCreate(['name' => 'sales-assistant']);
        $salesAssistantRole->syncPermissions($salesAssistantPermissions);

        // Define permissions for Store Manager
        $storeManagerPermissions = [
            'manage-products',
            'manage-promotions',
            'manage-suppliers',
            'manage-staff',
            'view-reports',
        ];

        // Create Store Manager role and assign permissions
        $storeManagerRole = Role::firstOrCreate(['name' => 'store-manager']);
        $storeManagerRole->syncPermissions(array_merge($storeManagerPermissions, $salesAssistantPermissions));

        // Create Admin role and assign all permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions(Permission::all());
    }
}