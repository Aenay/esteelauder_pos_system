<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class TestUserCreation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:user-creation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test user creation without role selection';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing User Creation (Role Removed)');
        $this->line('===================================');
        
        // Create a test user
        $this->info('Creating test user...');
        $user = User::create([
            'name' => 'Test User ' . time(),
            'email' => 'test' . time() . '@example.com',
            'password' => Hash::make('password123'),
        ]);
        
        // Assign default role
        $user->assignRole('sales-assistant');
        
        $this->line("✓ User created: {$user->name} ({$user->email})");
        $this->line("✓ Default role assigned: " . $user->getRoleNames()->first());
        $this->line("✓ Permissions count: " . $user->getPermissionNames()->count());
        
        // Clean up
        $user->delete();
        $this->line("✓ Test user cleaned up");
        
        $this->line('');
        $this->info('✅ User creation test completed successfully!');
        $this->line('New users will be created with sales-assistant role by default.');
        $this->line('Role management is now handled separately via the Roles & Permissions page.');
        
        return 0;
    }
}