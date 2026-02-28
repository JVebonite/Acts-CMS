@extends('layouts.app')

@section('title', $visitor->full_name . ' - ACTS Church CMS')
@section('page-title', $visitor->full_name)
@section('page-description', 'Visitor details')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="gradient-navy p-6">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 rounded-full bg-amber-100 flex items-center justify-center">
                    <span class="text-gold-700 text-xl font-bold">{{ substr($visitor->first_name, 0, 1) }}{{ substr($visitor->last_name, 0, 1) }}</span>
                </div>
                <div>
                    <h3 class="text-white font-bold text-lg">{{ $visitor->full_name }}</h3>
                    <p class="text-gold-400 text-sm">Visited {{ $visitor->visit_date->format('M d, Y') }}</p>
                </div>
                <div class="ml-auto">
                    @if($visitor->converted_to_member)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-500 text-white">
                        <i class="fas fa-check mr-1"></i> Converted to Member
                    </span>
                    @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                        {{ $visitor->follow_up_status === 'completed' ? 'bg-green-500 text-white' : 'bg-amber-500 text-white' }}">
                        {{ ucfirst(str_replace('_', ' ', $visitor->follow_up_status)) }}
                    </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Phone</p>
                    <p class="text-sm text-gray-800 mt-0.5">{{ $visitor->phone ?: '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Email</p>
                    <p class="text-sm text-gray-800 mt-0.5">{{ $visitor->email ?: '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Address</p>
                    <p class="text-sm text-gray-800 mt-0.5">{{ $visitor->address ?: '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Invited By</p>
                    <p class="text-sm text-gray-800 mt-0.5">{{ $visitor->invited_by ?: '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Service Attended</p>
                    <p class="text-sm text-gray-800 mt-0.5">{{ $visitor->service_attended ?: '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Follow-up Status</p>
                    <p class="text-sm text-gray-800 mt-0.5">{{ ucfirst(str_replace('_', ' ', $visitor->follow_up_status)) }}</p>
                </div>
            </div>

            @if($visitor->prayer_request)
            <div class="mt-6 pt-4 border-t border-gray-100">
                <p class="text-xs text-gray-500 uppercase tracking-wider">Prayer Request</p>
                <p class="text-sm text-gray-700 mt-1">{{ $visitor->prayer_request }}</p>
            </div>
            @endif

            @if($visitor->follow_up_notes)
            <div class="mt-4 pt-4 border-t border-gray-100">
                <p class="text-xs text-gray-500 uppercase tracking-wider">Follow-up Notes</p>
                <p class="text-sm text-gray-700 mt-1">{{ $visitor->follow_up_notes }}</p>
            </div>
            @endif

            @if($visitor->notes)
            <div class="mt-4 pt-4 border-t border-gray-100">
                <p class="text-xs text-gray-500 uppercase tracking-wider">Notes</p>
                <p class="text-sm text-gray-700 mt-1">{{ $visitor->notes }}</p>
            </div>
            @endif
        </div>

        <div class="border-t border-gray-100 px-6 py-4 flex items-center justify-between">
            <a href="{{ route('visitors.index') }}" class="text-sm text-gray-600 hover:text-gray-800">
                <i class="fas fa-arrow-left mr-1"></i> Back to Visitors
            </a>
            <div class="flex space-x-2">
                <a href="{{ route('visitors.edit', $visitor) }}" class="px-4 py-2 text-sm text-gold-600 border border-gold-300 rounded-lg hover:bg-gold-50">
                    <i class="fas fa-edit mr-1"></i> Edit
                </a>
                @if(!$visitor->converted_to_member)
                <form method="POST" action="{{ route('visitors.convert', $visitor) }}" onsubmit="return confirm('Convert this visitor to a member?')">
                    @csrf
                    <button type="submit" class="px-4 py-2 text-sm gradient-gold text-white rounded-lg hover:opacity-90">
                        <i class="fas fa-user-check mr-1"></i> Convert to Member
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
