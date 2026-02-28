@extends('layouts.app')

@section('title', 'Record Attendance - ACTS Church CMS')
@section('page-title', 'Record Attendance')
@section('page-description', 'Mark members present for a service')

@section('content')
<div class="max-w-4xl">
    <form method="POST" action="{{ route('attendance.store') }}">
        @csrf
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <h3 class="text-sm font-semibold text-navy-800 mb-4 flex items-center">
                <i class="fas fa-calendar-day mr-2 text-gold-500"></i> Service Details
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Service Date <span class="text-red-500">*</span></label>
                    <input type="date" name="service_date" value="{{ old('service_date', now()->toDateString()) }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500 focus:border-gold-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Service Type</label>
                    <select name="service_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                        <option value="sunday_service">Sunday Service</option>
                        <option value="midweek">Midweek Service</option>
                        <option value="prayer_meeting">Prayer Meeting</option>
                        <option value="special">Special Service</option>
                        <option value="youth">Youth Service</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Check-in Method</label>
                    <select name="check_in_method" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                        <option value="manual">Manual</option>
                        <option value="qr">QR Code</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6" x-data="{ search: '', selectAll: false }">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-navy-800 flex items-center">
                    <i class="fas fa-users mr-2 text-gold-500"></i> Select Members
                </h3>
                <div class="flex items-center space-x-3">
                    <label class="flex items-center text-sm text-gray-600 cursor-pointer">
                        <input type="checkbox" x-model="selectAll"
                               @change="document.querySelectorAll('.member-checkbox').forEach(cb => cb.checked = selectAll)"
                               class="rounded border-gray-300 text-gold-500 mr-2">
                        Select All
                    </label>
                </div>
            </div>

            <div class="mb-4">
                <input type="text" x-model="search" placeholder="Search members..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500 focus:border-gold-500">
            </div>

            <div class="max-h-96 overflow-y-auto space-y-1 border border-gray-200 rounded-lg p-2">
                @foreach($members as $member)
                <label class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-50 cursor-pointer member-row"
                       x-show="'{{ strtolower($member->full_name) }}'.includes(search.toLowerCase()) || search === ''">
                    <input type="checkbox" name="members[]" value="{{ $member->id }}"
                           class="member-checkbox rounded border-gray-300 text-gold-500 focus:ring-gold-500"
                           {{ in_array($member->id, old('members', [])) ? 'checked' : '' }}>
                    <div class="ml-3 flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-full gradient-navy flex items-center justify-center flex-shrink-0">
                            <span class="text-white text-xs font-bold">{{ substr($member->first_name, 0, 1) }}</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $member->full_name }}</p>
                            <p class="text-xs text-gray-500">{{ $member->phone ?: 'No phone' }}</p>
                        </div>
                    </div>
                </label>
                @endforeach

                @if($members->isEmpty())
                <p class="text-center text-gray-500 text-sm py-6">No active members found. <a href="{{ route('members.create') }}" class="text-gold-600">Add members first.</a></p>
                @endif
            </div>
        </div>

        <div class="flex items-center justify-end space-x-3">
            <a href="{{ route('attendance.index') }}" class="px-5 py-2.5 text-sm text-gray-600 border border-gray-300 rounded-lg">Cancel</a>
            <button type="submit" class="px-5 py-2.5 gradient-gold text-white text-sm font-medium rounded-lg hover:opacity-90 shadow-sm">
                <i class="fas fa-save mr-2"></i> Save Attendance
            </button>
        </div>
    </form>
</div>
@endsection
