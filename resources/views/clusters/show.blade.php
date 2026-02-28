@extends('layouts.app')

@section('title', $cluster->name . ' - ACTS Church CMS')
@section('page-title', $cluster->name)
@section('page-description', 'Cluster details and members')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Cluster Info --}}
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="gradient-navy p-6 text-center">
                <div class="w-16 h-16 rounded-full gradient-gold mx-auto mb-3 flex items-center justify-center">
                    <i class="fas fa-people-arrows text-white text-xl"></i>
                </div>
                <h3 class="text-white font-bold text-lg">{{ $cluster->name }}</h3>
                <p class="text-gold-400 text-sm mt-1">{{ $cluster->members->count() }} members</p>
            </div>
            <div class="p-5 space-y-3">
                @if($cluster->leader)
                <div class="flex items-center text-sm">
                    <i class="fas fa-user-tie w-5 text-gold-500"></i>
                    <span class="ml-3 text-gray-700">{{ $cluster->leader->full_name }} (Leader)</span>
                </div>
                @endif
                @if($cluster->meeting_day)
                <div class="flex items-center text-sm">
                    <i class="fas fa-calendar-alt w-5 text-gold-500"></i>
                    <span class="ml-3 text-gray-700">{{ ucfirst($cluster->meeting_day) }} {{ $cluster->meeting_time ? '@ ' . $cluster->meeting_time : '' }}</span>
                </div>
                @endif
                @if($cluster->location)
                <div class="flex items-center text-sm">
                    <i class="fas fa-map-marker-alt w-5 text-gold-500"></i>
                    <span class="ml-3 text-gray-700">{{ $cluster->location }}</span>
                </div>
                @endif
                <div class="flex items-center text-sm">
                    <i class="fas fa-info-circle w-5 text-gold-500"></i>
                    <span class="ml-3 text-gray-700">{{ ucfirst($cluster->status) }}</span>
                </div>
            </div>
            @if($cluster->description)
            <div class="border-t border-gray-100 px-5 py-3">
                <p class="text-xs text-gray-500">{{ $cluster->description }}</p>
            </div>
            @endif
            <div class="border-t border-gray-100 px-5 py-3 flex space-x-2">
                <a href="{{ route('clusters.edit', $cluster) }}" class="flex-1 text-center py-2 text-sm text-gold-600 hover:bg-gold-50 rounded-lg">
                    <i class="fas fa-edit mr-1"></i> Edit
                </a>
            </div>
        </div>
    </div>

    <div class="lg:col-span-2 space-y-6">
        {{-- Add Member --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-sm font-semibold text-navy-800 mb-3"><i class="fas fa-user-plus mr-2 text-gold-500"></i>Add Member</h3>
            <form method="POST" action="{{ route('clusters.members.add', $cluster) }}" class="flex flex-wrap items-end gap-3">
                @csrf
                <div class="flex-1 min-w-[200px]">
                    <select name="member_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                        <option value="">Select a member</option>
                        @foreach($availableMembers as $m)
                        <option value="{{ $m->id }}">{{ $m->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <input type="text" name="role" placeholder="Role (optional)" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                </div>
                <button type="submit" class="px-4 py-2 gradient-gold text-white text-sm rounded-lg hover:opacity-90">
                    <i class="fas fa-plus mr-1"></i> Add
                </button>
            </form>
        </div>

        {{-- Members List --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-5 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-navy-800">Members ({{ $cluster->members->count() }})</h3>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($cluster->members as $member)
                <div class="px-5 py-3 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-full gradient-navy flex items-center justify-center flex-shrink-0">
                            <span class="text-white text-xs font-bold">{{ substr($member->first_name, 0, 1) }}</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $member->full_name }}</p>
                            <p class="text-xs text-gray-500">{{ $member->pivot->role ?? 'Member' }} &middot; {{ $member->phone ?: 'No phone' }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('clusters.members.remove', [$cluster, $member]) }}" onsubmit="return confirm('Remove this member?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600"><i class="fas fa-times text-sm"></i></button>
                    </form>
                </div>
                @empty
                <p class="px-5 py-8 text-center text-gray-500 text-sm">No members in this cluster yet.</p>
                @endforelse
            </div>
        </div>

        {{-- Recent Follow-ups --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-navy-800">Recent Follow-ups</h3>
                <a href="{{ route('clusters.followups', ['cluster_id' => $cluster->id]) }}" class="text-xs text-gold-600 hover:text-gold-700">View All</a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($cluster->followups->take(5) as $fu)
                <div class="px-5 py-3">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm font-medium text-gray-900">{{ $fu->member->full_name ?? 'Unknown' }}</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium
                            {{ $fu->status === 'completed' ? 'bg-green-100 text-green-700' :
                               ($fu->status === 'no_response' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                            {{ ucfirst(str_replace('_', ' ', $fu->status)) }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-500">{{ ucfirst(str_replace('_', ' ', $fu->type)) }} &middot; {{ $fu->follow_up_date->format('M d, Y') }}</p>
                    @if($fu->notes)
                    <p class="text-xs text-gray-600 mt-1">{{ Str::limit($fu->notes, 100) }}</p>
                    @endif
                </div>
                @empty
                <p class="px-5 py-6 text-center text-gray-500 text-sm">No follow-ups recorded.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
