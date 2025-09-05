<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionController extends Controller
{

    public function index()
    {
        // Check if user has admin role
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized access. Admin role required.');
        }
        
        $users = User::orderBy('name')->get();
        $roles = Role::orderBy('name')->get();
        $permissions = Permission::orderBy('name')->get();
        return view('admin.roles_permissions.index', compact('users', 'roles', 'permissions'));
    }

    public function update(Request $request, User $user)
    {
        // Check if user has admin role
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized access. Admin role required.');
        }
        
        $validated = $request->validate([
            'roles' => ['array'],
            'permissions' => ['array'],
        ]);

        $roles = $validated['roles'] ?? [];
        $permissions = $validated['permissions'] ?? [];

        // Debug logging
        \Log::info('Role Permission Update', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'roles' => $roles,
            'permissions' => $permissions,
            'request_data' => $request->all()
        ]);

        // Admins manage others but cannot remove their own admin role via this page
        if ($user->id === auth()->id()) {
            // Ensure admin keeps admin if they have it
            if ($user->hasRole('admin') && ! in_array('admin', $roles, true)) {
                $roles[] = 'admin';
            }
        }

        $user->syncRoles($roles);
        $user->syncPermissions($permissions);

        return redirect()->route('admin.roles-permissions.index')->with('success', 'Roles and permissions updated for '.$user->name);
    }
}
