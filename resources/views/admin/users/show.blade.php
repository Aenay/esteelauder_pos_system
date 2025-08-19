<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <div class="mb-4">
                        <x-input-label for="name" :value="__('Name')" />
                        <div class="mt-1 text-gray-800">{{ $user->name }}</div>
                    </div>

                    <div class="mb-4">
                        <x-input-label for="email" :value="__('Email')" />
                        <div class="mt-1 text-gray-800">{{ $user->email }}</div>
                    </div>

                    <div class="mb-4">
                        <x-input-label for="created_at" :value="__('Created At')" />
                        <div class="mt-1 text-gray-800">{{ $user->created_at->format('M d, Y H:i A') }}</div>
                    </div>

                    <div class="mb-4">
                        <x-input-label for="updated_at" :value="__('Updated At')" />
                        <div class="mt-1 text-gray-800">{{ $user->updated_at->format('M d, Y H:i A') }}</div>
                    </div>

                    <div class="flex items-center gap-4">
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                            {{ __('Edit') }}
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-gray-900">
                            {{ __('Back to Users') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
