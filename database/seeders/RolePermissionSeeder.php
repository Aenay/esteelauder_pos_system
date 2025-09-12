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
            // Admin & System
            'manage-users',
            'assign-roles',
            'configure-settings',
            'view-system-logs',
            'manage-backups',
            'system-maintenance',

            // Products Management
            'manage-products',
            'view-products',
            'create-products',
            'edit-products',
            'delete-products',
            'manage-inventory',
            'view-inventory',
            'update-inventory',

            // Orders Management
            'orders.view',
            'orders.create',
            'orders.edit',
            'orders.delete',
            'orders.print',
            'orders.refund',
            'orders.cancel',

            // Customers Management
            'manage-customers',
            'view-customers',
            'create-customers',
            'edit-customers',
            'delete-customers',
            'view-customer-history',

            // Staff Management
            'manage-staff',
            'view-staff',
            'create-staff',
            'edit-staff',
            'delete-staff',
            'view-staff-performance',
            'manage-staff-schedules',

            // Suppliers Management
            'manage-suppliers',
            'view-suppliers',
            'create-suppliers',
            'edit-suppliers',
            'delete-suppliers',
            'manage-purchase-orders',

            // Deliveries Management
            'manage-deliveries',
            'view-deliveries',
            'create-deliveries',
            'edit-deliveries',
            'delete-deliveries',
            'update-delivery-status',

            // Branches Management
            'manage-branches',
            'view-branches',
            'create-branches',
            'edit-branches',
            'delete-branches',
            'view-branch-analytics',

            // Promotions Management
            'manage-promotions',
            'view-promotions',
            'create-promotions',
            'edit-promotions',
            'delete-promotions',
            'toggle-promotions',

            // Loyalty Management
            'manage-loyalty',
            'view-loyalty',
            'add-loyalty-points',
            'use-loyalty-points',
            'view-loyalty-analytics',

            // Reports & Analytics
            'view-reports',
            'view-sales-reports',
            'view-staff-reports',
            'view-customer-reports',
            'view-inventory-reports',
            'export-reports',

            // POS Operations
            'create-orders',
            'process-payments',
            'apply-promotions',
            'process-returns',
            'handle-refunds',
            'manage-cash-drawer',

            // Financial
            'view-financials',
            'manage-pricing',
            'view-profit-loss',
            'manage-discounts',

            // Security & Access
            'view-audit-logs',
            'manage-permissions',
            'view-user-activity',
            'reset-passwords',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Define permissions for Sales Assistant
        $salesAssistantPermissions = [
            'create-orders',
            'process-payments',
            'manage-customers',
            'view-customers',
            'create-customers',
            'edit-customers',
            'apply-promotions',
            'process-returns',
            'orders.view',
            'orders.print',
            'orders.create',
            'view-products',
            'view-inventory',
            'handle-refunds',
            'manage-cash-drawer',
        ];

        // Create Sales Assistant role and assign permissions
        $salesAssistantRole = Role::firstOrCreate(['name' => 'sales-assistant', 'guard_name' => 'web']);
        $salesAssistantRole->syncPermissions($salesAssistantPermissions);

        // Define permissions for Store Manager
        $storeManagerPermissions = [
            'manage-products',
            'view-products',
            'create-products',
            'edit-products',
            'delete-products',
            'manage-inventory',
            'view-inventory',
            'update-inventory',
            'manage-promotions',
            'view-promotions',
            'create-promotions',
            'edit-promotions',
            'delete-promotions',
            'toggle-promotions',
            'manage-suppliers',
            'view-suppliers',
            'create-suppliers',
            'edit-suppliers',
            'delete-suppliers',
            'manage-purchase-orders',
            'manage-staff',
            'view-staff',
            'create-staff',
            'edit-staff',
            'delete-staff',
            'view-staff-performance',
            'manage-staff-schedules',
            'view-reports',
            'view-sales-reports',
            'view-staff-reports',
            'view-customer-reports',
            'view-inventory-reports',
            'export-reports',
            'manage-deliveries',
            'view-deliveries',
            'create-deliveries',
            'edit-deliveries',
            'delete-deliveries',
            'update-delivery-status',
            'manage-branches',
            'view-branches',
            'create-branches',
            'edit-branches',
            'delete-branches',
            'view-branch-analytics',
            'manage-loyalty',
            'view-loyalty',
            'add-loyalty-points',
            'use-loyalty-points',
            'view-loyalty-analytics',
            'view-financials',
            'manage-pricing',
            'view-profit-loss',
            'manage-discounts',
        ];

        // Create Store Manager role and assign permissions
        $storeManagerRole = Role::firstOrCreate(['name' => 'store-manager', 'guard_name' => 'web']);
        $storeManagerRole->syncPermissions(array_merge($storeManagerPermissions, $salesAssistantPermissions, [
            'orders.edit',
            'orders.delete',
        ]));

        // Create Admin role and assign all permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions(Permission::all());
    }
}