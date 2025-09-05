<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class FixRolesAndPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:roles-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix role conflicts and ensure proper permissions are assigned';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fixing Roles and Permissions...');
        $this->line('================================');
        
        // Clear all existing roles and permissions from users
        $this->info('Clearing existing user roles and permissions...');
        User::all()->each(function($user) {
            $user->syncRoles([]);
            $user->syncPermissions([]);
        });
        
        // Ensure all permissions exist
        $this->info('Creating/updating permissions...');
        $permissions = [
            'manage-users',
            'assign-roles', 
            'configure-settings',
            'manage-products',
            'manage-promotions',
            'manage-suppliers',
            'manage-staff',
            'view-reports',
            'create-orders',
            'process-payments',
            'manage-customers',
            'apply-promotions',
            'process-returns',
        ];
        
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
        
        // Create/update roles with proper permissions
        $this->info('Creating/updating roles...');
        
        // Admin role - all permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions($permissions);
        $this->line('✓ Admin role created with all permissions');
        
        // Store Manager role - management permissions
        $storeManagerRole = Role::firstOrCreate(['name' => 'store-manager']);
        $storeManagerPermissions = [
            'manage-products',
            'manage-promotions', 
            'manage-suppliers',
            'manage-staff',
            'view-reports',
            'create-orders',
            'process-payments',
            'manage-customers',
            'apply-promotions',
            'process-returns',
        ];
        $storeManagerRole->syncPermissions($storeManagerPermissions);
        $this->line('✓ Store Manager role created with management permissions');
        
        // Sales Assistant role - basic POS permissions
        $salesAssistantRole = Role::firstOrCreate(['name' => 'sales-assistant']);
        $salesAssistantPermissions = [
            'create-orders',
            'process-payments',
            'manage-customers',
            'apply-promotions',
        ];
        $salesAssistantRole->syncPermissions($salesAssistantPermissions);
        $this->line('✓ Sales Assistant role created with POS permissions');
        
        // Assign roles to users
        $this->info('Assigning roles to users...');
        
        $users = User::all();
        foreach ($users as $user) {
            if ($user->name === 'Test User') {
                $user->assignRole('admin');
                $user->givePermissionTo($permissions); // Give admin all permissions
                $this->line("✓ Assigned admin role to: {$user->name}");
            } elseif ($user->name === 'mananger') {
                $user->assignRole('store-manager');
                $user->givePermissionTo($storeManagerPermissions); // Give store manager permissions
                $this->line("✓ Assigned store-manager role to: {$user->name}");
            } elseif ($user->name === 'lily') {
                $user->assignRole('sales-assistant');
                $user->givePermissionTo($salesAssistantPermissions); // Give sales assistant permissions
                $this->line("✓ Assigned sales-assistant role to: {$user->name}");
            } else {
                // Default new users to sales-assistant
                $user->assignRole('sales-assistant');
                $user->givePermissionTo($salesAssistantPermissions);
                $this->line("✓ Assigned sales-assistant role to: {$user->name}");
            }
        }
        
        $this->line('');
        $this->info('Role and Permission System Fixed!');
        $this->line('================================');
        
        // Show final status
        $this->line('Final Status:');
        User::all()->each(function($user) {
            $this->line("- {$user->name}: " . $user->getRoleNames()->implode(', ') . " (" . $user->getPermissionNames()->count() . " permissions)");
        });
        
        return 0;
    }
}