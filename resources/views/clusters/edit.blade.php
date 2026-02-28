@extends('layouts.app')

@section('title', 'Edit Cluster - ACTS Church CMS')
@section('page-title', 'Edit Cluster')
@section('page-description', 'Update cluster information')

@section('content')
<div class="max-w-3xl">
    <form method="POST" action="{{ route('clusters.update', $cluster) }}">
        @csrf
        @method('PUT')
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <h3 class="text-sm font-semibold text-navy-800 mb-4 flex items-center">
                <i class="fas fa-people-arrows mr-2 text-gold-500"></i> Cluster Details
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cluster Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $cluster->name) }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Leader</label>
                    <select name="leader_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                        <option value="">Select Leader</option>
                        @foreach($members as $m)
                        <option value="{{ $m->id }}" {{ old('leader_id', $cluster->leader_id) == $m->id ? 'selected' : '' }}>{{ $m->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Meeting Day</label>
                    <select name="meeting_day" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                        <option value="">Select Day</option>
                        @foreach(['monday','tuesday','wednesday','thursday','friday','saturday','sunday'] as $day)
                        <option value="{{ $day }}" {{ old('meeting_day', $cluster->meeting_day) === $day ? 'selected' : '' }}>{{ ucfirst($day) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Meeting Time</label>
                    <input type="time" name="meeting_time" value="{{ old('meeting_time', $cluster->meeting_time) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                    <input type="text" name="location" value="{{ old('location', $cluster->location) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                        <option value="active" {{ old('status', $cluster->status) === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $cluster->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">{{ old('description', $cluster->description) }}</textarea>
                </div>
            </div>
        </div>
        <div class="flex items-center justify-end space-x-3">
            <a href="{{ route('clusters.show', $cluster) }}" class="px-5 py-2.5 text-sm text-gray-600 border border-gray-300 rounded-lg">Cancel</a>
            <button type="submit" class="px-5 py-2.5 gradient-gold text-white text-sm font-medium rounded-lg hover:opacity-90 shadow-sm">
                <i class="fas fa-save mr-2"></i> Update Cluster
            </button>
        </div>
    </form>
</div>
@endsection
