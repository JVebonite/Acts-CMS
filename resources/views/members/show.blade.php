@extends('layouts.app')

@section('title', $member->full_name . ' - ACTS Church CMS')
@section('page-title', $member->full_name)
@section('page-description', 'Member profile and details')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Profile Card --}}
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="gradient-navy p-6 text-center">
                <div class="w-20 h-20 rounded-full mx-auto mb-3 border-4 border-gold-400 overflow-hidden">
                    @if($member->profile_photo)
                        <img src="{{ asset('storage/' . $member->profile_photo) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full gradient-gold flex items-center justify-center">
                            <span class="text-white text-2xl font-bold">{{ substr($member->first_name, 0, 1) }}{{ substr($member->last_name, 0, 1) }}</span>
                        </div>
                    @endif
                </div>
                <h3 class="text-white font-bold text-lg">{{ $member->full_name }}</h3>
                <p class="text-gold-400 text-sm mt-1">{{ ucfirst($member->membership_status) }} Member</p>
            </div>
            <div class="p-5 space-y-3">
                @if($member->phone)
                <div class="flex items-center text-sm">
                    <i class="fas fa-phone w-5 text-gold-500"></i>
                    <span class="ml-3 text-gray-700">{{ $member->phone }}</span>
                </div>
                @endif
                @if($member->email)
                <div class="flex items-center text-sm">
                    <i class="fas fa-envelope w-5 text-gold-500"></i>
                    <span class="ml-3 text-gray-700">{{ $member->email }}</span>
                </div>
                @endif
                @if($member->address)
                <div class="flex items-start text-sm">
                    <i class="fas fa-map-marker-alt w-5 text-gold-500 mt-0.5"></i>
                    <span class="ml-3 text-gray-700">{{ $member->address }}{{ $member->city ? ', ' . $member->city : '' }}</span>
                </div>
                @endif
                @if($member->date_of_birth)
                <div class="flex items-center text-sm">
                    <i class="fas fa-birthday-cake w-5 text-gold-500"></i>
                    <span class="ml-3 text-gray-700">{{ $member->date_of_birth->format('M d, Y') }}</span>
                </div>
                @endif
                @if($member->membership_date)
                <div class="flex items-center text-sm">
                    <i class="fas fa-calendar-check w-5 text-gold-500"></i>
                    <span class="ml-3 text-gray-700">Joined {{ $member->membership_date->format('M d, Y') }}</span>
                </div>
                @endif
                @if($member->family)
                <div class="flex items-center text-sm">
                    <i class="fas fa-home w-5 text-gold-500"></i>
                    <span class="ml-3 text-gray-700">{{ $member->family->family_name }} {{ $member->family_role ? '(' . $member->family_role . ')' : '' }}</span>
                </div>
                @endif
            </div>
            <div class="border-t border-gray-100 px-5 py-3 flex space-x-2">
                <a href="{{ route('members.edit', $member) }}" class="flex-1 text-center py-2 text-sm text-gold-600 hover:bg-gold-50 rounded-lg transition-colors">
                    <i class="fas fa-edit mr-1"></i> Edit
                </a>
                <form method="POST" action="{{ route('members.destroy', $member) }}" class="flex-1"
                      onsubmit="return confirm('Are you sure?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                        <i class="fas fa-trash mr-1"></i> Delete
                    </button>
                </form>
            </div>
        </div>

        {{-- Emergency Contact --}}
        @if($member->emergency_contact_name)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mt-6">
            <h4 class="text-sm font-semibold text-navy-800 mb-3"><i class="fas fa-phone-alt mr-2 text-gold-500"></i>Emergency Contact</h4>
            <p class="text-sm text-gray-700 font-medium">{{ $member->emergency_contact_name }}</p>
            <p class="text-sm text-gray-500">{{ $member->emergency_contact_phone }}</p>
            <p class="text-xs text-gray-400">{{ $member->emergency_contact_relationship }}</p>
        </div>
        @endif
    </div>

    {{-- Details --}}
    <div class="lg:col-span-2 space-y-6">
        {{-- Quick Stats --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
                <p class="text-2xl font-bold text-navy-800">{{ $member->attendances->count() }}</p>
                <p class="text-xs text-gray-500 mt-1">Attendances</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
                <p class="text-2xl font-bold text-navy-800">{{ number_format($member->donations->sum('amount'), 2) }}</p>
                <p class="text-xs text-gray-500 mt-1">Total Given</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
                <p class="text-2xl font-bold text-navy-800">{{ $member->pledges->count() }}</p>
                <p class="text-xs text-gray-500 mt-1">Pledges</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
                <p class="text-2xl font-bold text-navy-800">{{ $member->clusters->count() }}</p>
                <p class="text-xs text-gray-500 mt-1">Clusters</p>
            </div>
        </div>

        {{-- Additional Details --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h4 class="text-sm font-semibold text-navy-800 mb-4"><i class="fas fa-info-circle mr-2 text-gold-500"></i>Additional Details</h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Gender</p>
                    <p class="text-sm text-gray-800 mt-0.5">{{ $member->gender ? ucfirst($member->gender) : '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Marital Status</p>
                    <p class="text-sm text-gray-800 mt-0.5">{{ $member->marital_status ? ucfirst($member->marital_status) : '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Occupation</p>
                    <p class="text-sm text-gray-800 mt-0.5">{{ $member->occupation ?: '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Employer</p>
                    <p class="text-sm text-gray-800 mt-0.5">{{ $member->employer ?: '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Baptism Date</p>
                    <p class="text-sm text-gray-800 mt-0.5">{{ $member->baptism_date ? $member->baptism_date->format('M d, Y') : '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Wedding Anniversary</p>
                    <p class="text-sm text-gray-800 mt-0.5">{{ $member->wedding_anniversary ? $member->wedding_anniversary->format('M d, Y') : '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Membership Class</p>
                    <p class="text-sm text-gray-800 mt-0.5">{{ $member->membership_class ?: '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">QR Code</p>
                    <p class="text-sm text-gray-800 mt-0.5 font-mono text-xs">{{ $member->qr_code ?: '-' }}</p>
                </div>
            </div>
            @if($member->notes)
            <div class="mt-4 pt-4 border-t border-gray-100">
                <p class="text-xs text-gray-500 uppercase tracking-wider">Notes</p>
                <p class="text-sm text-gray-700 mt-1">{{ $member->notes }}</p>
            </div>
            @endif
        </div>

        {{-- Recent Attendance --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h4 class="text-sm font-semibold text-navy-800 mb-4"><i class="fas fa-clipboard-check mr-2 text-gold-500"></i>Recent Attendance</h4>
            <div class="space-y-2">
                @forelse($member->attendances->sortByDesc('service_date')->take(10) as $att)
                <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                    <div>
                        <p class="text-sm text-gray-800">{{ $att->service_date->format('M d, Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $att->service_type ?: 'Service' }} &middot; {{ ucfirst($att->check_in_method) }}</p>
                    </div>
                    <span class="text-xs text-gray-500">{{ $att->check_in_time }}</span>
                </div>
                @empty
                <p class="text-sm text-gray-500 text-center py-4">No attendance records.</p>
                @endforelse
            </div>
        </div>

        {{-- Documents --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h4 class="text-sm font-semibold text-navy-800 mb-4"><i class="fas fa-file-alt mr-2 text-gold-500"></i>Documents</h4>
            @forelse($member->documents as $doc)
            <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-file text-gray-400"></i>
                    <div>
                        <p class="text-sm text-gray-800">{{ $doc->title }}</p>
                        <p class="text-xs text-gray-500">{{ ucfirst($doc->category) }}</p>
                    </div>
                </div>
                <a href="{{ asset('storage/' . $doc->file_path) }}" class="text-gold-600 text-sm hover:text-gold-700" target="_blank">
                    <i class="fas fa-download"></i>
                </a>
            </div>
            @empty
            <p class="text-sm text-gray-500 text-center py-4">No documents uploaded.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
