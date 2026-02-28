@extends('layouts.app')

@section('title', 'Visitors - ACTS Church CMS')
@section('page-title', 'Visitors')
@section('page-description', 'Track and manage church visitors')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h3 class="text-base font-semibold text-navy-800">All Visitors</h3>
            <p class="text-xs text-gray-500 mt-0.5">{{ $visitors->total() }} total visitors</p>
        </div>
        <a href="{{ route('visitors.create') }}"
           class="inline-flex items-center px-4 py-2 gradient-gold text-white text-sm font-medium rounded-lg hover:opacity-90 shadow-sm">
            <i class="fas fa-plus mr-2"></i> Add Visitor
        </a>
    </div>

    <div class="p-4 border-b border-gray-50 bg-gray-50/50">
        <form method="GET" action="{{ route('visitors.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search visitors..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500 focus:border-gold-500">
            </div>
            <select name="follow_up_status" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('follow_up_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="contacted" {{ request('follow_up_status') === 'contacted' ? 'selected' : '' }}>Contacted</option>
                <option value="follow_up" {{ request('follow_up_status') === 'follow_up' ? 'selected' : '' }}>Follow Up</option>
                <option value="completed" {{ request('follow_up_status') === 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-navy-600 text-white text-sm rounded-lg hover:bg-navy-700">
                <i class="fas fa-search mr-1"></i> Filter
            </button>
            <a href="{{ route('visitors.index') }}" class="px-4 py-2 text-gray-600 text-sm hover:text-gray-800">Reset</a>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Visitor</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Contact</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Visit Date</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Invited By</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Follow-up</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($visitors as $visitor)
                <tr class="hover:bg-gray-50/50">
                    <td class="px-5 py-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-9 h-9 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
                                <span class="text-gold-700 text-xs font-bold">{{ substr($visitor->first_name, 0, 1) }}{{ substr($visitor->last_name, 0, 1) }}</span>
                            </div>
                            <div>
                                <a href="{{ route('visitors.show', $visitor) }}" class="text-sm font-medium text-navy-800 hover:text-gold-600">{{ $visitor->full_name }}</a>
                                @if($visitor->converted_to_member)
                                <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-green-100 text-green-700">Converted</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3">
                        <p class="text-sm text-gray-900">{{ $visitor->phone ?: '-' }}</p>
                        <p class="text-xs text-gray-500">{{ $visitor->email ?: '-' }}</p>
                    </td>
                    <td class="px-5 py-3 text-sm text-gray-600">{{ $visitor->visit_date->format('M d, Y') }}</td>
                    <td class="px-5 py-3 text-sm text-gray-600">{{ $visitor->invited_by ?: '-' }}</td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $visitor->follow_up_status === 'completed' ? 'bg-green-100 text-green-700' :
                               ($visitor->follow_up_status === 'contacted' ? 'bg-blue-100 text-blue-700' :
                               ($visitor->follow_up_status === 'follow_up' ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-600')) }}">
                            {{ ucfirst(str_replace('_', ' ', $visitor->follow_up_status)) }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-right">
                        <div class="flex items-center justify-end space-x-1">
                            <a href="{{ route('visitors.show', $visitor) }}" class="p-1.5 text-gray-400 hover:text-navy-600" title="View"><i class="fas fa-eye text-sm"></i></a>
                            <a href="{{ route('visitors.edit', $visitor) }}" class="p-1.5 text-gray-400 hover:text-gold-600" title="Edit"><i class="fas fa-edit text-sm"></i></a>
                            @if(!$visitor->converted_to_member)
                            <form method="POST" action="{{ route('visitors.convert', $visitor) }}" class="inline" onsubmit="return confirm('Convert this visitor to a member?')">
                                @csrf
                                <button type="submit" class="p-1.5 text-gray-400 hover:text-green-600" title="Convert to Member"><i class="fas fa-user-check text-sm"></i></button>
                            </form>
                            @endif
                            <form method="POST" action="{{ route('visitors.destroy', $visitor) }}" class="inline" onsubmit="return confirm('Delete this visitor?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600" title="Delete"><i class="fas fa-trash text-sm"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-12 text-center">
                        <i class="fas fa-user-plus text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500 text-sm">No visitors found.</p>
                        <a href="{{ route('visitors.create') }}" class="mt-2 text-gold-600 text-sm hover:text-gold-700">Record a visitor</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($visitors->hasPages())
    <div class="px-5 py-3 border-t border-gray-100">{{ $visitors->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
