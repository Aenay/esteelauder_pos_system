<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class AssignAdminRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:assign-admin {user_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign admin role to a user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user_id');
        
        if ($userId) {
            $user = User::find($userId);
        } else {
            $user = User::first();
        }
        
        if (!$user) {
            $this->error('No user found!');
            return 1;
        }
        
        $user->assignRole('admin');
        $this->info("Admin role assigned to: {$user->name} ({$user->email})");
        
        // Also assign all permissions to admin
        $user->givePermissionTo([
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
        ]);
        
        $this->info('All permissions assigned to admin user.');
        
        return 0;
    }
}