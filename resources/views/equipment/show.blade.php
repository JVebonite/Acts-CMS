@extends('layouts.app')

@section('title', $equipment->name . ' - ACTS Church CMS')
@section('page-title', $equipment->name)
@section('page-description', 'Equipment details')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="gradient-navy p-6">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 rounded-lg bg-white/10 flex items-center justify-center">
                    <i class="fas fa-tools text-gold-400 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-white font-bold text-lg">{{ $equipment->name }}</h3>
                    <p class="text-gray-300 text-sm">{{ $equipment->category ?: 'Uncategorized' }}</p>
                </div>
                <div class="ml-auto flex space-x-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                        {{ $equipment->status === 'available' ? 'bg-green-500 text-white' :
                           ($equipment->status === 'in_use' ? 'bg-blue-500 text-white' :
                           ($equipment->status === 'maintenance' ? 'bg-amber-500 text-white' : 'bg-gray-500 text-white')) }}">
                        {{ ucfirst(str_replace('_', ' ', $equipment->status)) }}
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                        {{ $equipment->condition === 'new' || $equipment->condition === 'good' ? 'bg-green-500/20 text-green-300' :
                           ($equipment->condition === 'fair' ? 'bg-amber-500/20 text-amber-300' : 'bg-red-500/20 text-red-300') }}">
                        {{ ucfirst($equipment->condition) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Serial Number</p>
                    <p class="text-sm text-gray-800 mt-0.5 font-mono">{{ $equipment->serial_number ?: '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Location</p>
                    <p class="text-sm text-gray-800 mt-0.5">{{ $equipment->location ?: '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Assigned To</p>
                    <p class="text-sm text-gray-800 mt-0.5">{{ $equipment->assigned_to ?: '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Purchase Date</p>
                    <p class="text-sm text-gray-800 mt-0.5">{{ $equipment->purchase_date ? $equipment->purchase_date->format('M d, Y') : '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Purchase Price</p>
                    <p class="text-sm text-gray-800 mt-0.5">{{ $equipment->purchase_price ? number_format($equipment->purchase_price, 2) : '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Last Maintenance</p>
                    <p class="text-sm text-gray-800 mt-0.5">{{ $equipment->last_maintenance_date ? $equipment->last_maintenance_date->format('M d, Y') : '-' }}</p>
                </div>
            </div>

            @if($equipment->description)
            <div class="mt-6 pt-4 border-t border-gray-100">
                <p class="text-xs text-gray-500 uppercase tracking-wider">Description</p>
                <p class="text-sm text-gray-700 mt-1">{{ $equipment->description }}</p>
            </div>
            @endif

            @if($equipment->notes)
            <div class="mt-4 pt-4 border-t border-gray-100">
                <p class="text-xs text-gray-500 uppercase tracking-wider">Notes</p>
                <p class="text-sm text-gray-700 mt-1">{{ $equipment->notes }}</p>
            </div>
            @endif
        </div>

        <div class="border-t border-gray-100 px-6 py-4 flex items-center justify-between">
            <a href="{{ route('equipment.index') }}" class="text-sm text-gray-600 hover:text-gray-800">
                <i class="fas fa-arrow-left mr-1"></i> Back
            </a>
            <div class="flex space-x-2">
                <a href="{{ route('equipment.edit', $equipment) }}" class="px-4 py-2 text-sm text-gold-600 border border-gold-300 rounded-lg hover:bg-gold-50">
                    <i class="fas fa-edit mr-1"></i> Edit
                </a>
                <form method="POST" action="{{ route('equipment.destroy', $equipment) }}" onsubmit="return confirm('Delete this equipment?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="px-4 py-2 text-sm text-red-600 border border-red-300 rounded-lg hover:bg-red-50">
                        <i class="fas fa-trash mr-1"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
