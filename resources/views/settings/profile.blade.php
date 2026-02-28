@extends('layouts.app')

@section('title', 'Profile - ACTS Church CMS')
@section('page-title', 'My Profile')
@section('page-description', 'Update your personal information')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-6">
    <div class="md:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <nav class="divide-y divide-gray-100">
                <a href="{{ route('settings.index') }}" class="block px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-cog w-5 mr-2"></i> General
                </a>
                <a href="{{ route('settings.profile') }}" class="block px-4 py-3 text-sm font-medium text-gold-600 bg-gold-50">
                    <i class="fas fa-user-circle w-5 mr-2"></i> Profile
                </a>
                <a href="{{ route('settings.users') }}" class="block px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-users-cog w-5 mr-2"></i> User Management
                </a>
            </nav>
        </div>
    </div>

    <div class="md:col-span-3 space-y-6">
        {{-- Profile Info --}}
        <form method="POST" action="{{ route('settings.profile.update') }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-sm font-semibold text-navy-800 mb-4 flex items-center">
                    <i class="fas fa-user mr-2 text-gold-500"></i> Profile Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Profile Photo</label>
                        <input type="file" name="profile_photo" accept="image/*"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:bg-gold-50 file:text-gold-700">
                    </div>
                </div>
                <div class="mt-4 flex justify-end">
                    <button type="submit" class="px-5 py-2.5 gradient-gold text-white text-sm font-medium rounded-lg hover:opacity-90">
                        <i class="fas fa-save mr-2"></i> Update Profile
                    </button>
                </div>
            </div>
        </form>

        {{-- Change Password --}}
        <form method="POST" action="{{ route('settings.password.update') }}">
            @csrf @method('PUT')
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-sm font-semibold text-navy-800 mb-4 flex items-center">
                    <i class="fas fa-lock mr-2 text-gold-500"></i> Change Password
                </h3>
                <div class="space-y-4 max-w-md">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                        <input type="password" name="current_password" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                        <input type="password" name="password" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                        <input type="password" name="password_confirmation" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                    </div>
                </div>
                <div class="mt-4 flex justify-end">
                    <button type="submit" class="px-5 py-2.5 bg-navy-600 text-white text-sm font-medium rounded-lg hover:bg-navy-700">
                        <i class="fas fa-key mr-2"></i> Change Password
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
