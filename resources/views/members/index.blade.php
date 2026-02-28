@extends('layouts.app')

@section('title', 'Members - ACTS Church CMS')
@section('page-title', 'Members')
@section('page-description', 'Manage church membership records')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    {{-- Header --}}
    <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h3 class="text-base font-semibold text-navy-800">All Members</h3>
            <p class="text-xs text-gray-500 mt-0.5">{{ $members->total() }} total members</p>
        </div>
        <a href="{{ route('members.create') }}"
           class="inline-flex items-center px-4 py-2 gradient-gold text-white text-sm font-medium rounded-lg hover:opacity-90 transition-opacity shadow-sm">
            <i class="fas fa-plus mr-2"></i> Add Member
        </a>
    </div>

    {{-- Filters --}}
    <div class="p-4 border-b border-gray-50 bg-gray-50/50">
        <form method="GET" action="{{ route('members.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, or phone..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500 focus:border-gold-500">
            </div>
            <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                <option value="transferred" {{ request('status') === 'transferred' ? 'selected' : '' }}>Transferred</option>
            </select>
            <select name="gender" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                <option value="">All Genders</option>
                <option value="male" {{ request('gender') === 'male' ? 'selected' : '' }}>Male</option>
                <option value="female" {{ request('gender') === 'female' ? 'selected' : '' }}>Female</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-navy-600 text-white text-sm rounded-lg hover:bg-navy-700">
                <i class="fas fa-search mr-1"></i> Filter
            </button>
            <a href="{{ route('members.index') }}" class="px-4 py-2 text-gray-600 text-sm hover:text-gray-800">Reset</a>
        </form>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Member</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Contact</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Family</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Joined</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($members as $member)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-9 h-9 rounded-full gradient-navy flex items-center justify-center flex-shrink-0">
                                @if($member->profile_photo)
                                    <img src="{{ asset('storage/' . $member->profile_photo) }}" class="w-9 h-9 rounded-full object-cover">
                                @else
                                    <span class="text-white text-xs font-bold">{{ substr($member->first_name, 0, 1) }}{{ substr($member->last_name, 0, 1) }}</span>
                                @endif
                            </div>
                            <div>
                                <a href="{{ route('members.show', $member) }}" class="text-sm font-medium text-navy-800 hover:text-gold-600">
                                    {{ $member->full_name }}
                                </a>
                                <p class="text-xs text-gray-500">{{ $member->gender ? ucfirst($member->gender) : '-' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3">
                        <p class="text-sm text-gray-900">{{ $member->phone ?: '-' }}</p>
                        <p class="text-xs text-gray-500">{{ $member->email ?: '-' }}</p>
                    </td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $member->membership_status === 'active' ? 'bg-green-100 text-green-700' :
                               ($member->membership_status === 'inactive' ? 'bg-gray-100 text-gray-600' : 'bg-amber-100 text-amber-700') }}">
                            {{ ucfirst($member->membership_status) }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-sm text-gray-600">{{ $member->family ? $member->family->family_name : '-' }}</td>
                    <td class="px-5 py-3 text-sm text-gray-600">{{ $member->membership_date ? $member->membership_date->format('M d, Y') : '-' }}</td>
                    <td class="px-5 py-3 text-right">
                        <div class="flex items-center justify-end space-x-1">
                            <a href="{{ route('members.show', $member) }}" class="p-1.5 text-gray-400 hover:text-navy-600" title="View">
                                <i class="fas fa-eye text-sm"></i>
                            </a>
                            <a href="{{ route('members.edit', $member) }}" class="p-1.5 text-gray-400 hover:text-gold-600" title="Edit">
                                <i class="fas fa-edit text-sm"></i>
                            </a>
                            <form method="POST" action="{{ route('members.destroy', $member) }}" class="inline"
                                  onsubmit="return confirm('Are you sure you want to delete this member?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600" title="Delete">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-users text-4xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500 text-sm">No members found.</p>
                            <a href="{{ route('members.create') }}" class="mt-2 text-gold-600 text-sm hover:text-gold-700">Add your first member</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($members->hasPages())
    <div class="px-5 py-3 border-t border-gray-100">
        {{ $members->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
