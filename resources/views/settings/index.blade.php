@extends('layouts.app')

@section('title', 'Settings - ACTS Church CMS')
@section('page-title', 'Settings')
@section('page-description', 'System configuration and preferences')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-6">
    {{-- Sidebar Nav --}}
    <div class="md:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <nav class="divide-y divide-gray-100">
                <a href="{{ route('settings.index') }}" class="block px-4 py-3 text-sm font-medium {{ request()->routeIs('settings.index') ? 'text-gold-600 bg-gold-50 border-l-3 border-gold-500' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-cog w-5 mr-2"></i> General
                </a>
                <a href="{{ route('settings.profile') }}" class="block px-4 py-3 text-sm font-medium {{ request()->routeIs('settings.profile') ? 'text-gold-600 bg-gold-50' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-user-circle w-5 mr-2"></i> Profile
                </a>
                <a href="{{ route('settings.users') }}" class="block px-4 py-3 text-sm font-medium {{ request()->routeIs('settings.users') ? 'text-gold-600 bg-gold-50' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-users-cog w-5 mr-2"></i> User Management
                </a>
            </nav>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="md:col-span-3">
        <form method="POST" action="{{ route('settings.update') }}">
            @csrf
            @foreach($settings as $group => $items)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
                <h3 class="text-sm font-semibold text-navy-800 mb-4 capitalize flex items-center">
                    <i class="fas fa-sliders-h mr-2 text-gold-500"></i> {{ $group ?: 'General' }} Settings
                </h3>
                <div class="space-y-4">
                    @foreach($items as $setting)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            {{ ucwords(str_replace(['_', '.'], ' ', $setting->key)) }}
                        </label>
                        @if(strlen($setting->value) > 100)
                        <textarea name="settings[{{ $setting->key }}]" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">{{ $setting->value }}</textarea>
                        @else
                        <input type="text" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                        @endif
                        @if($setting->description)
                        <p class="text-xs text-gray-500 mt-1">{{ $setting->description }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach

            @if($settings->isEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center">
                <i class="fas fa-cog text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500 text-sm">No settings configured yet.</p>
                <p class="text-xs text-gray-400 mt-1">Settings will appear here once created.</p>
            </div>
            @else
            <div class="flex justify-end">
                <button type="submit" class="px-5 py-2.5 gradient-gold text-white text-sm font-medium rounded-lg hover:opacity-90 shadow-sm">
                    <i class="fas fa-save mr-2"></i> Save Settings
                </button>
            </div>
            @endif
        </form>
    </div>
</div>
@endsection
