@extends('layouts.app')

@section('content')
<main class="flex-1 flex flex-col overflow-hidden">
    <header class="bg-white shadow">
        <div class="px-6 py-5 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Roles & Permissions</h1>
                <p class="text-sm text-gray-500 mt-1">Assign roles and granular permissions to users. Only admins can access this page.</p>
                @if(session('success'))
                    <div class="mt-3 inline-flex items-center text-green-700 bg-green-100 border border-green-200 px-3 py-1.5 rounded">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    </div>
                @endif
            </div>
        </div>
    </header>

    <div class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
        <div class="mx-auto max-w-6xl">
            <!-- User Picker Card -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 items-center">
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select User</label>
                        <select id="user-select" class="w-full p-2.5 border rounded focus:ring-2 focus:ring-pink-500">
                            <option value="">-- Choose user --</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-2">Tip: You can search permissions below to quickly find capabilities.</p>
                    </div>
                    <div class="lg:justify-self-end">
                        <div class="text-sm text-gray-600">Assigned to selected user:</div>
                        <div id="assigned-badges" class="mt-2 flex flex-wrap gap-2"></div>
                    </div>
                </div>
            </div>

            <!-- Roles & Permissions -->
            <form id="rp-form" method="POST" class="relative">
                @csrf
                @method('PATCH')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Roles Card -->
                    <div class="bg-white rounded-lg shadow p-5">
                        <div class="flex items-center justify-between mb-3">
                            <h2 class="font-semibold text-gray-800">Roles</h2>
                            <div class="space-x-2 text-xs">
                                <button type="button" id="roles-check-all" class="px-2 py-1 rounded border text-gray-600 hover:bg-gray-50">Select all</button>
                                <button type="button" id="roles-uncheck-all" class="px-2 py-1 rounded border text-gray-600 hover:bg-gray-50">Clear</button>
                            </div>
                        </div>
                        <div class="border rounded">
                            <div class="p-2 border-b bg-gray-50">
                                <input type="text" id="roles-filter" placeholder="Filter roles..." class="w-full p-2 text-sm border rounded focus:ring-2 focus:ring-pink-500">
                            </div>
                            <div class="max-h-72 overflow-auto p-3 space-y-2" id="roles-list">
                                @foreach($roles as $role)
                                <label class="flex items-center justify-between p-2 rounded hover:bg-gray-50">
                                    <div class="flex items-center space-x-2">
                                        <input type="checkbox" name="roles[]" value="{{ $role->name }}" class="role-checkbox">
                                        <span class="text-sm">{{ ucfirst($role->name) }}</span>
                                    </div>
                                    <span class="text-[10px] uppercase tracking-wide px-2 py-0.5 rounded bg-gray-100 text-gray-600">Role</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Permissions Card -->
                    <div class="bg-white rounded-lg shadow p-5">
                        <div class="flex items-center justify-between mb-3">
                            <h2 class="font-semibold text-gray-800">Permissions</h2>
                            <div class="space-x-2 text-xs">
                                <button type="button" id="perms-check-all" class="px-2 py-1 rounded border text-gray-600 hover:bg-gray-50">Select all</button>
                                <button type="button" id="perms-uncheck-all" class="px-2 py-1 rounded border text-gray-600 hover:bg-gray-50">Clear</button>
                            </div>
                        </div>
                        <div class="border rounded">
                            <div class="p-2 border-b bg-gray-50">
                                <input type="text" id="perms-filter" placeholder="Filter permissions..." class="w-full p-2 text-sm border rounded focus:ring-2 focus:ring-pink-500">
                            </div>
                            <div class="max-h-72 overflow-auto p-3 grid grid-cols-1 gap-2" id="perms-list">
                                @foreach($permissions as $perm)
                                <label class="flex items-center justify-between p-2 rounded hover:bg-gray-50">
                                    <div class="flex items-center space-x-2">
                                        <input type="checkbox" name="permissions[]" value="{{ $perm->name }}" class="perm-checkbox">
                                        <span class="text-sm">{{ $perm->name }}</span>
                                    </div>
                                    <span class="text-[10px] uppercase tracking-wide px-2 py-0.5 rounded bg-gray-100 text-gray-600">Perm</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sticky Save Bar -->
                <div class="sticky bottom-4 mt-6 flex justify-end">
                    <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white font-semibold px-5 py-2 rounded shadow disabled:opacity-50" disabled id="save-btn">
                        <i class="fas fa-save mr-2"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

@php
    $usersForJs = $users->map(function ($u) {
        return [
            'id' => $u->id,
            'roles' => $u->getRoleNames(),
            'perms' => $u->getPermissionNames(),
            'name' => $u->name,
        ];
    })->values();
@endphp

<script>
    const users = @json($usersForJs);

    function badge(text, type='role'){ return `<span class="inline-flex items-center text-xs px-2 py-0.5 rounded ${type==='role'?'bg-blue-50 text-blue-700':'bg-violet-50 text-violet-700'}">${text}</span>`; }

    function renderBadges(user){
        const wrap = document.getElementById('assigned-badges');
        if(!user){ wrap.innerHTML = ''; return; }
        const roleBadges = user.roles.map(r=>badge(r,'role')).join(' ');
        const permBadges = user.perms.slice(0,6).map(p=>badge(p,'perm')).join(' ');
        const more = user.perms.length>6 ? `<span class="text-xs text-gray-500">+${user.perms.length-6} more</span>` : '';
        wrap.innerHTML = roleBadges + ' ' + permBadges + ' ' + more;
    }

    function loadUser(userId) {
        const user = users.find(u => String(u.id) === String(userId));
        document.querySelectorAll('.role-checkbox').forEach(cb => cb.checked = !!user && user.roles.includes(cb.value));
        document.querySelectorAll('.perm-checkbox').forEach(cb => cb.checked = !!user && user.perms.includes(cb.value));
        const form = document.getElementById('rp-form');
        form.action = `{{ url('admin/roles-permissions') }}/${userId}`;
        document.getElementById('save-btn').disabled = !userId;
        renderBadges(user);
    }

    // Filters
    function filterList(inputId, listSelector){
        const val = document.getElementById(inputId).value.toLowerCase();
        document.querySelectorAll(listSelector + ' label').forEach(el=>{
            const text = el.innerText.toLowerCase();
            el.style.display = text.includes(val) ? '' : 'none';
        });
    }
    document.getElementById('roles-filter').addEventListener('input', ()=>filterList('roles-filter','#roles-list'));
    document.getElementById('perms-filter').addEventListener('input', ()=>filterList('perms-filter','#perms-list'));

    // Select/Clear all
    document.getElementById('roles-check-all').addEventListener('click', ()=>document.querySelectorAll('.role-checkbox').forEach(cb=>cb.checked=true));
    document.getElementById('roles-uncheck-all').addEventListener('click', ()=>document.querySelectorAll('.role-checkbox').forEach(cb=>cb.checked=false));
    document.getElementById('perms-check-all').addEventListener('click', ()=>document.querySelectorAll('.perm-checkbox').forEach(cb=>cb.checked=true));
    document.getElementById('perms-uncheck-all').addEventListener('click', ()=>document.querySelectorAll('.perm-checkbox').forEach(cb=>cb.checked=false));

    // Change user
    document.getElementById('user-select').addEventListener('change', (e) => loadUser(e.target.value));
</script>
@endsection
