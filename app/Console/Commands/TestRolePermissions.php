<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class TestRolePermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:role-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test role and permission system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Role and Permission System');
        $this->line('================================');
        
        $user = User::first();
        
        if (!$user) {
            $this->error('No users found!');
            return 1;
        }
        
        $this->info("User: {$user->name} ({$user->email})");
        $this->line("Has admin role: " . ($user->hasRole('admin') ? 'Yes' : 'No'));
        $this->line("Can manage users: " . ($user->can('manage-users') ? 'Yes' : 'No'));
        $this->line("Can assign roles: " . ($user->can('assign-roles') ? 'Yes' : 'No'));
        $this->line("Roles: " . $user->getRoleNames()->implode(', '));
        $this->line("Permissions count: " . $user->getPermissionNames()->count());
        
        $this->line("\nAll Users and their roles:");
        $this->line("==========================");
        
        User::all()->each(function($u) {
            $this->line("{$u->name}: " . $u->getRoleNames()->implode(', ') . " (" . $u->getPermissionNames()->count() . " permissions)");
        });
        
        $this->line("\nAvailable Roles:");
        $this->line("================");
        \Spatie\Permission\Models\Role::all()->each(function($role) {
            $this->line("- {$role->name} (" . $role->permissions->count() . " permissions)");
        });
        
        $this->line("\nAvailable Permissions:");
        $this->line("======================");
        \Spatie\Permission\Models\Permission::all()->each(function($perm) {
            $this->line("- {$perm->name}");
        });
        
        return 0;
    }
}