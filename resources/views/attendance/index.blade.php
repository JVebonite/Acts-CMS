@extends('layouts.app')

@section('title', 'Attendance - ACTS Church CMS')
@section('page-title', 'Attendance')
@section('page-description', 'Track church service attendance')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    @foreach($attendanceSummary->take(3) as $summary)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($summary->service_date)->format('M d, Y') }}</p>
                <p class="text-2xl font-bold text-navy-800 mt-1">{{ $summary->total }}</p>
                <p class="text-xs text-gray-500">attendees</p>
            </div>
            <a href="{{ route('attendance.show', $summary->service_date) }}"
               class="w-10 h-10 rounded-lg bg-navy-50 flex items-center justify-center text-navy-600 hover:bg-navy-100">
                <i class="fas fa-eye text-sm"></i>
            </a>
        </div>
    </div>
    @endforeach
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h3 class="text-base font-semibold text-navy-800">Attendance Records</h3>
            <p class="text-xs text-gray-500 mt-0.5">{{ $attendances->total() }} total records</p>
        </div>
        <a href="{{ route('attendance.create') }}"
           class="inline-flex items-center px-4 py-2 gradient-gold text-white text-sm font-medium rounded-lg hover:opacity-90 shadow-sm">
            <i class="fas fa-plus mr-2"></i> Record Attendance
        </a>
    </div>

    <div class="p-4 border-b border-gray-50 bg-gray-50/50">
        <form method="GET" action="{{ route('attendance.index') }}" class="flex flex-wrap items-center gap-3">
            <div>
                <input type="date" name="date" value="{{ request('date') }}"
                       class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500 focus:border-gold-500">
            </div>
            <select name="service_type" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                <option value="">All Services</option>
                <option value="sunday_service" {{ request('service_type') === 'sunday_service' ? 'selected' : '' }}>Sunday Service</option>
                <option value="midweek" {{ request('service_type') === 'midweek' ? 'selected' : '' }}>Midweek Service</option>
                <option value="prayer_meeting" {{ request('service_type') === 'prayer_meeting' ? 'selected' : '' }}>Prayer Meeting</option>
                <option value="special" {{ request('service_type') === 'special' ? 'selected' : '' }}>Special Service</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-navy-600 text-white text-sm rounded-lg hover:bg-navy-700">
                <i class="fas fa-search mr-1"></i> Filter
            </button>
            <a href="{{ route('attendance.index') }}" class="px-4 py-2 text-gray-600 text-sm">Reset</a>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Member</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Service</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Check-in</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Method</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($attendances as $att)
                <tr class="hover:bg-gray-50/50">
                    <td class="px-5 py-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-full gradient-navy flex items-center justify-center flex-shrink-0">
                                <span class="text-white text-xs font-bold">{{ substr($att->member->first_name ?? 'N', 0, 1) }}</span>
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ $att->member->full_name ?? 'Unknown' }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-sm text-gray-600">{{ $att->service_date->format('M d, Y') }}</td>
                    <td class="px-5 py-3 text-sm text-gray-600">{{ $att->service_type ? ucfirst(str_replace('_', ' ', $att->service_type)) : '-' }}</td>
                    <td class="px-5 py-3 text-sm text-gray-600">{{ $att->check_in_time ?? '-' }}</td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                            {{ $att->check_in_method === 'qr' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                            {{ strtoupper($att->check_in_method) }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-right">
                        <form method="POST" action="{{ route('attendance.destroy', $att) }}" class="inline" onsubmit="return confirm('Remove this record?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600"><i class="fas fa-trash text-sm"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-12 text-center">
                        <i class="fas fa-clipboard-check text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500 text-sm">No attendance records found.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($attendances->hasPages())
    <div class="px-5 py-3 border-t border-gray-100">{{ $attendances->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
