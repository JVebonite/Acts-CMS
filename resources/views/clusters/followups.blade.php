@extends('layouts.app')

@section('title', 'Cluster Follow-ups - ACTS Church CMS')
@section('page-title', 'Cluster Follow-ups')
@section('page-description', 'Track and manage member follow-ups')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <h3 class="text-base font-semibold text-navy-800">Follow-up Records</h3>
                <form method="GET" class="flex items-center gap-2">
                    <select name="cluster_id" onchange="this.form.submit()" class="px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                        <option value="">All Clusters</option>
                        @foreach($clusters as $c)
                        <option value="{{ $c->id }}" {{ request('cluster_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                    <select name="status" onchange="this.form.submit()" class="px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="no_response" {{ request('status') === 'no_response' ? 'selected' : '' }}>No Response</option>
                        <option value="rescheduled" {{ request('status') === 'rescheduled' ? 'selected' : '' }}>Rescheduled</option>
                    </select>
                </form>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse($followups as $fu)
                <div class="px-5 py-4" x-data="{ editing: false }">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start space-x-3">
                            <div class="w-9 h-9 rounded-full gradient-navy flex items-center justify-center flex-shrink-0 mt-0.5">
                                <span class="text-white text-xs font-bold">{{ substr($fu->member->first_name ?? '', 0, 1) }}</span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $fu->member->full_name ?? 'Unknown' }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ $fu->cluster->name ?? '' }} &middot;
                                    {{ ucfirst(str_replace('_', ' ', $fu->type)) }} &middot;
                                    {{ $fu->follow_up_date->format('M d, Y') }}
                                </p>
                                @if($fu->followUpPerson)
                                <p class="text-xs text-gray-400">By: {{ $fu->followUpPerson->full_name }}</p>
                                @endif
                                @if($fu->notes)
                                <p class="text-sm text-gray-600 mt-1">{{ $fu->notes }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $fu->status === 'completed' ? 'bg-green-100 text-green-700' :
                                   ($fu->status === 'no_response' ? 'bg-red-100 text-red-700' :
                                   ($fu->status === 'rescheduled' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700')) }}">
                                {{ ucfirst(str_replace('_', ' ', $fu->status)) }}
                            </span>
                            <button @click="editing = !editing" class="p-1 text-gray-400 hover:text-gold-600"><i class="fas fa-pen text-xs"></i></button>
                        </div>
                    </div>

                    {{-- Inline Edit --}}
                    <form method="POST" action="{{ route('clusters.followups.update', $fu) }}" x-show="editing" x-cloak class="mt-3 flex items-end gap-3 pl-12">
                        @csrf @method('PUT')
                        <select name="status" class="px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                            <option value="pending" {{ $fu->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ $fu->status === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="no_response" {{ $fu->status === 'no_response' ? 'selected' : '' }}>No Response</option>
                            <option value="rescheduled" {{ $fu->status === 'rescheduled' ? 'selected' : '' }}>Rescheduled</option>
                        </select>
                        <input type="text" name="notes" value="{{ $fu->notes }}" placeholder="Notes..." class="flex-1 px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                        <button type="submit" class="px-3 py-1.5 gradient-gold text-white text-sm rounded-lg hover:opacity-90">Update</button>
                    </form>
                </div>
                @empty
                <div class="px-5 py-12 text-center">
                    <i class="fas fa-clipboard-list text-4xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500 text-sm">No follow-ups found.</p>
                </div>
                @endforelse
            </div>
            @if($followups->hasPages())
            <div class="px-5 py-3 border-t border-gray-100">{{ $followups->withQueryString()->links() }}</div>
            @endif
        </div>
    </div>

    {{-- New Follow-up Form --}}
    <div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-sm font-semibold text-navy-800 mb-4"><i class="fas fa-plus-circle mr-2 text-gold-500"></i>Record Follow-up</h3>
            <form method="POST" action="{{ route('clusters.followups.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cluster <span class="text-red-500">*</span></label>
                    <select name="cluster_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                        <option value="">Select Cluster</option>
                        @foreach($clusters as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Member <span class="text-red-500">*</span></label>
                    <select name="member_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                        <option value="">Select Member</option>
                    </select>
                    <p class="text-xs text-gray-400 mt-1">Select cluster first to load members</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date <span class="text-red-500">*</span></label>
                    <input type="date" name="follow_up_date" value="{{ now()->toDateString() }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type <span class="text-red-500">*</span></label>
                    <select name="type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                        <option value="phone_call">Phone Call</option>
                        <option value="visit">Visit</option>
                        <option value="message">Message</option>
                        <option value="email">Email</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                    <select name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                        <option value="no_response">No Response</option>
                        <option value="rescheduled">Rescheduled</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500"></textarea>
                </div>
                <button type="submit" class="w-full px-4 py-2 gradient-gold text-white text-sm font-medium rounded-lg hover:opacity-90">
                    <i class="fas fa-save mr-1"></i> Save Follow-up
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
