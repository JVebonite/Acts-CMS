@extends('layouts.app')

@section('title', 'Add Equipment - ACTS Church CMS')
@section('page-title', 'Add Equipment')
@section('page-description', 'Register new church equipment')

@section('content')
<div class="max-w-3xl">
    <form method="POST" action="{{ route('equipment.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <h3 class="text-sm font-semibold text-navy-800 mb-4 flex items-center">
                <i class="fas fa-tools mr-2 text-gold-500"></i> Equipment Details
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <input type="text" name="category" value="{{ old('category') }}" placeholder="e.g., Audio, Furniture"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Serial Number</label>
                    <input type="text" name="serial_number" value="{{ old('serial_number') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                    <input type="text" name="location" value="{{ old('location') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Condition <span class="text-red-500">*</span></label>
                    <select name="condition" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                        <option value="new" {{ old('condition') === 'new' ? 'selected' : '' }}>New</option>
                        <option value="good" {{ old('condition', 'good') === 'good' ? 'selected' : '' }}>Good</option>
                        <option value="fair" {{ old('condition') === 'fair' ? 'selected' : '' }}>Fair</option>
                        <option value="poor" {{ old('condition') === 'poor' ? 'selected' : '' }}>Poor</option>
                        <option value="damaged" {{ old('condition') === 'damaged' ? 'selected' : '' }}>Damaged</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                    <select name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                        <option value="available" {{ old('status', 'available') === 'available' ? 'selected' : '' }}>Available</option>
                        <option value="in_use" {{ old('status') === 'in_use' ? 'selected' : '' }}>In Use</option>
                        <option value="maintenance" {{ old('status') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="retired" {{ old('status') === 'retired' ? 'selected' : '' }}>Retired</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Purchase Date</label>
                    <input type="date" name="purchase_date" value="{{ old('purchase_date') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Purchase Price</label>
                    <input type="number" name="purchase_price" step="0.01" min="0" value="{{ old('purchase_price') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Assigned To</label>
                    <input type="text" name="assigned_to" value="{{ old('assigned_to') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Photo</label>
                    <input type="file" name="photo" accept="image/*"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:bg-gold-50 file:text-gold-700">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">{{ old('description') }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea name="notes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>
        <div class="flex items-center justify-end space-x-3">
            <a href="{{ route('equipment.index') }}" class="px-5 py-2.5 text-sm text-gray-600 border border-gray-300 rounded-lg">Cancel</a>
            <button type="submit" class="px-5 py-2.5 gradient-gold text-white text-sm font-medium rounded-lg hover:opacity-90 shadow-sm">
                <i class="fas fa-save mr-2"></i> Save Equipment
            </button>
        </div>
    </form>
</div>
@endsection
