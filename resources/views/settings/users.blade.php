@extends('layouts.app')

@section('title', 'User Management - ACTS Church CMS')
@section('page-title', 'User Management')
@section('page-description', 'Manage admin users and roles')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-6">
    <div class="md:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <nav class="divide-y divide-gray-100">
                <a href="{{ route('settings.index') }}" class="block px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-cog w-5 mr-2"></i> General
                </a>
                <a href="{{ route('settings.profile') }}" class="block px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-user-circle w-5 mr-2"></i> Profile
                </a>
                <a href="{{ route('settings.users') }}" class="block px-4 py-3 text-sm font-medium text-gold-600 bg-gold-50">
                    <i class="fas fa-users-cog w-5 mr-2"></i> User Management
                </a>
            </nav>
        </div>
    </div>

    <div class="md:col-span-3 space-y-6">
        {{-- Add User Form --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-semibold text-navy-800 mb-4 flex items-center">
                <i class="fas fa-user-plus mr-2 text-gold-500"></i> Add New User
            </h3>
            <form method="POST" action="{{ route('settings.users.store') }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password_confirmation" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role <span class="text-red-500">*</span></label>
                        <select name="role" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                            <option value="admin">Admin</option>
                            <option value="super_admin">Super Admin</option>
                            <option value="finance">Finance</option>
                            <option value="attendance">Attendance</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                    </div>
                </div>
                <div class="mt-4 flex justify-end">
                    <button type="submit" class="px-5 py-2.5 gradient-gold text-white text-sm font-medium rounded-lg hover:opacity-90">
                        <i class="fas fa-user-plus mr-2"></i> Create User
                    </button>
                </div>
            </form>
        </div>

        {{-- Users List --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-5 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-navy-800">All Users ({{ $users->total() }})</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">User</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Role</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Last Login</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($users as $user)
                        <tr>
                            <td class="px-5 py-3">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-full gradient-navy flex items-center justify-center flex-shrink-0">
                                        <span class="text-white text-xs font-bold">{{ substr($user->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $user->role === 'super_admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                                    {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                </span>
                            </td>
                            <td class="px-5 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-sm text-gray-600">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</td>
                            <td class="px-5 py-3 text-right">
                                <form method="POST" action="{{ route('settings.users.toggle', $user) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="p-1.5 {{ $user->is_active ? 'text-gray-400 hover:text-red-600' : 'text-gray-400 hover:text-green-600' }}"
                                            title="{{ $user->is_active ? 'Deactivate' : 'Activate' }}">
                                        <i class="fas {{ $user->is_active ? 'fa-ban' : 'fa-check-circle' }} text-sm"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($users->hasPages())
            <div class="px-5 py-3 border-t border-gray-100">{{ $users->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
