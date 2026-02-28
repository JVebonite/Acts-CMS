@extends('layouts.app')

@section('title', 'Equipment - ACTS Church CMS')
@section('page-title', 'Equipment')
@section('page-description', 'Manage church equipment and assets')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h3 class="text-base font-semibold text-navy-800">Equipment Inventory</h3>
            <p class="text-xs text-gray-500 mt-0.5">{{ $equipment->total() }} items</p>
        </div>
        <a href="{{ route('equipment.create') }}"
           class="inline-flex items-center px-4 py-2 gradient-gold text-white text-sm font-medium rounded-lg hover:opacity-90 shadow-sm">
            <i class="fas fa-plus mr-2"></i> Add Equipment
        </a>
    </div>

    <div class="p-4 border-b border-gray-50 bg-gray-50/50">
        <form method="GET" action="{{ route('equipment.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search equipment..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500 focus:border-gold-500">
            </div>
            <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                <option value="">All Statuses</option>
                <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Available</option>
                <option value="in_use" {{ request('status') === 'in_use' ? 'selected' : '' }}>In Use</option>
                <option value="maintenance" {{ request('status') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                <option value="retired" {{ request('status') === 'retired' ? 'selected' : '' }}>Retired</option>
            </select>
            <select name="condition" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                <option value="">All Conditions</option>
                <option value="new" {{ request('condition') === 'new' ? 'selected' : '' }}>New</option>
                <option value="good" {{ request('condition') === 'good' ? 'selected' : '' }}>Good</option>
                <option value="fair" {{ request('condition') === 'fair' ? 'selected' : '' }}>Fair</option>
                <option value="poor" {{ request('condition') === 'poor' ? 'selected' : '' }}>Poor</option>
                <option value="damaged" {{ request('condition') === 'damaged' ? 'selected' : '' }}>Damaged</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-navy-600 text-white text-sm rounded-lg hover:bg-navy-700">
                <i class="fas fa-search mr-1"></i> Filter
            </button>
            <a href="{{ route('equipment.index') }}" class="px-4 py-2 text-gray-600 text-sm">Reset</a>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Equipment</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Category</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Location</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Condition</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($equipment as $item)
                <tr class="hover:bg-gray-50/50">
                    <td class="px-5 py-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-tools text-gray-500 text-sm"></i>
                            </div>
                            <div>
                                <a href="{{ route('equipment.show', $item) }}" class="text-sm font-medium text-navy-800 hover:text-gold-600">{{ $item->name }}</a>
                                @if($item->serial_number)
                                <p class="text-xs text-gray-500 font-mono">{{ $item->serial_number }}</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-sm text-gray-600">{{ $item->category ?: '-' }}</td>
                    <td class="px-5 py-3 text-sm text-gray-600">{{ $item->location ?: '-' }}</td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $item->condition === 'new' ? 'bg-green-100 text-green-700' :
                               ($item->condition === 'good' ? 'bg-blue-100 text-blue-700' :
                               ($item->condition === 'fair' ? 'bg-amber-100 text-amber-700' :
                               ($item->condition === 'poor' ? 'bg-orange-100 text-orange-700' : 'bg-red-100 text-red-700'))) }}">
                            {{ ucfirst($item->condition) }}
                        </span>
                    </td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $item->status === 'available' ? 'bg-green-100 text-green-700' :
                               ($item->status === 'in_use' ? 'bg-blue-100 text-blue-700' :
                               ($item->status === 'maintenance' ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-600')) }}">
                            {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-right">
                        <div class="flex items-center justify-end space-x-1">
                            <a href="{{ route('equipment.show', $item) }}" class="p-1.5 text-gray-400 hover:text-navy-600"><i class="fas fa-eye text-sm"></i></a>
                            <a href="{{ route('equipment.edit', $item) }}" class="p-1.5 text-gray-400 hover:text-gold-600"><i class="fas fa-edit text-sm"></i></a>
                            <form method="POST" action="{{ route('equipment.destroy', $item) }}" class="inline" onsubmit="return confirm('Delete this equipment?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600"><i class="fas fa-trash text-sm"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-12 text-center">
                        <i class="fas fa-tools text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500 text-sm">No equipment found.</p>
                        <a href="{{ route('equipment.create') }}" class="mt-2 text-gold-600 text-sm hover:text-gold-700">Add equipment</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($equipment->hasPages())
    <div class="px-5 py-3 border-t border-gray-100">{{ $equipment->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
