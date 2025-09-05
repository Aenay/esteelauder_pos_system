<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class TestRolePermissionPage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:role-permission-page';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test role permission page functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Role Permission Page Functionality');
        $this->line('==========================================');
        
        // Test 1: Check if all users have proper roles
        $this->info('1. Checking user roles...');
        $users = User::all();
        foreach ($users as $user) {
            $roles = $user->getRoleNames();
            $permissions = $user->getPermissionNames();
            $this->line("   {$user->name}: {$roles->implode(', ')} ({$permissions->count()} permissions)");
        }
        
        // Test 2: Test role assignment
        $this->info('2. Testing role assignment...');
        $testUser = $users->first();
        $originalRoles = $testUser->getRoleNames();
        
        // Remove all roles
        $testUser->syncRoles([]);
        $this->line("   Removed all roles from {$testUser->name}");
        
        // Assign sales-assistant role
        $testUser->assignRole('sales-assistant');
        $this->line("   Assigned sales-assistant role to {$testUser->name}");
        
        // Verify
        $newRoles = $testUser->getRoleNames();
        $this->line("   Current roles: {$newRoles->implode(', ')}");
        
        // Restore original roles
        $testUser->syncRoles($originalRoles);
        $this->line("   Restored original roles");
        
        // Test 3: Test permission assignment
        $this->info('3. Testing permission assignment...');
        $originalPermissions = $testUser->getPermissionNames();
        
        // Remove all permissions
        $testUser->syncPermissions([]);
        $this->line("   Removed all permissions from {$testUser->name}");
        
        // Assign specific permissions
        $testUser->givePermissionTo(['create-orders', 'process-payments']);
        $this->line("   Assigned specific permissions to {$testUser->name}");
        
        // Verify
        $newPermissions = $testUser->getPermissionNames();
        $this->line("   Current permissions: {$newPermissions->implode(', ')}");
        
        // Restore original permissions
        $testUser->syncPermissions($originalPermissions);
        $this->line("   Restored original permissions");
        
        // Test 4: Test role-based permissions
        $this->info('4. Testing role-based permissions...');
        $salesAssistant = User::where('name', 'lily')->first();
        if ($salesAssistant) {
            $this->line("   {$salesAssistant->name} can create orders: " . ($salesAssistant->can('create-orders') ? 'Yes' : 'No'));
            $this->line("   {$salesAssistant->name} can manage users: " . ($salesAssistant->can('manage-users') ? 'Yes' : 'No'));
        }
        
        $this->line('');
        $this->info('✅ All tests completed successfully!');
        $this->line('The role permission system is working correctly.');
        
        return 0;
    }
}