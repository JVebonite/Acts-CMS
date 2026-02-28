@extends('layouts.app')

@section('title', 'Clusters - ACTS Church CMS')
@section('page-title', 'Clusters')
@section('page-description', 'Manage church clusters and small groups')

@section('content')
<div class="flex flex-wrap gap-3 mb-6">
    <a href="{{ route('clusters.create') }}" class="inline-flex items-center px-4 py-2 gradient-gold text-white text-sm font-medium rounded-lg hover:opacity-90 shadow-sm">
        <i class="fas fa-plus mr-2"></i> New Cluster
    </a>
    <a href="{{ route('clusters.followups') }}" class="inline-flex items-center px-4 py-2 bg-white text-navy-700 text-sm font-medium rounded-lg border border-gray-200 hover:bg-gray-50">
        <i class="fas fa-clipboard-list mr-2"></i> Follow-ups
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="p-5 border-b border-gray-100">
        <h3 class="text-base font-semibold text-navy-800">All Clusters ({{ $clusters->total() }})</h3>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Cluster</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Leader</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Members</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Meeting</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($clusters as $cluster)
                <tr class="hover:bg-gray-50/50">
                    <td class="px-5 py-3">
                        <a href="{{ route('clusters.show', $cluster) }}" class="text-sm font-medium text-navy-800 hover:text-gold-600">{{ $cluster->name }}</a>
                        @if($cluster->location)
                        <p class="text-xs text-gray-500">{{ $cluster->location }}</p>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-sm text-gray-600">{{ $cluster->leader ? $cluster->leader->full_name : '-' }}</td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                            {{ $cluster->members_count }} members
                        </span>
                    </td>
                    <td class="px-5 py-3 text-sm text-gray-600">
                        {{ $cluster->meeting_day ? ucfirst($cluster->meeting_day) : '-' }}
                        {{ $cluster->meeting_time ? '@ ' . $cluster->meeting_time : '' }}
                    </td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $cluster->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ ucfirst($cluster->status) }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-right">
                        <div class="flex items-center justify-end space-x-1">
                            <a href="{{ route('clusters.show', $cluster) }}" class="p-1.5 text-gray-400 hover:text-navy-600"><i class="fas fa-eye text-sm"></i></a>
                            <a href="{{ route('clusters.edit', $cluster) }}" class="p-1.5 text-gray-400 hover:text-gold-600"><i class="fas fa-edit text-sm"></i></a>
                            <form method="POST" action="{{ route('clusters.destroy', $cluster) }}" class="inline" onsubmit="return confirm('Delete this cluster?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600"><i class="fas fa-trash text-sm"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-12 text-center">
                        <i class="fas fa-people-arrows text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500 text-sm">No clusters created yet.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($clusters->hasPages())
    <div class="px-5 py-3 border-t border-gray-100">{{ $clusters->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
