@extends('layouts.app')

@section('title', 'Attendance - ' . $date . ' - ACTS Church CMS')
@section('page-title', 'Attendance for ' . \Carbon\Carbon::parse($date)->format('M d, Y'))
@section('page-description', 'Detailed attendance breakdown')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 text-center">
        <p class="text-3xl font-bold text-green-600">{{ $attendances->count() }}</p>
        <p class="text-xs text-gray-500 mt-1">Present</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 text-center">
        <p class="text-3xl font-bold text-red-500">{{ $absentMembers->count() }}</p>
        <p class="text-xs text-gray-500 mt-1">Absent</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 text-center">
        @php $total = $attendances->count() + $absentMembers->count(); @endphp
        <p class="text-3xl font-bold text-navy-800">{{ $total > 0 ? round(($attendances->count() / $total) * 100) : 0 }}%</p>
        <p class="text-xs text-gray-500 mt-1">Attendance Rate</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Present --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-5 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-green-700"><i class="fas fa-check-circle mr-2"></i>Present ({{ $attendances->count() }})</h3>
        </div>
        <div class="divide-y divide-gray-50 max-h-96 overflow-y-auto">
            @forelse($attendances as $att)
            <div class="px-5 py-3 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-full gradient-navy flex items-center justify-center flex-shrink-0">
                        <span class="text-white text-xs font-bold">{{ substr($att->member->first_name ?? 'N', 0, 1) }}</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $att->member->full_name ?? 'Unknown' }}</p>
                        <p class="text-xs text-gray-500">{{ $att->check_in_time ?? '-' }} &middot; {{ strtoupper($att->check_in_method) }}</p>
                    </div>
                </div>
                <span class="w-2 h-2 rounded-full bg-green-500"></span>
            </div>
            @empty
            <p class="px-5 py-8 text-center text-gray-500 text-sm">No one marked present.</p>
            @endforelse
        </div>
    </div>

    {{-- Absent --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-5 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-red-600"><i class="fas fa-times-circle mr-2"></i>Absent ({{ $absentMembers->count() }})</h3>
        </div>
        <div class="divide-y divide-gray-50 max-h-96 overflow-y-auto">
            @forelse($absentMembers as $member)
            <div class="px-5 py-3 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center flex-shrink-0">
                        <span class="text-gray-600 text-xs font-bold">{{ substr($member->first_name, 0, 1) }}</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $member->full_name }}</p>
                        <p class="text-xs text-gray-500">{{ $member->phone ?: '-' }}</p>
                    </div>
                </div>
                <span class="w-2 h-2 rounded-full bg-red-400"></span>
            </div>
            @empty
            <p class="px-5 py-8 text-center text-gray-500 text-sm">Everyone was present!</p>
            @endforelse
        </div>
    </div>
</div>

<div class="mt-6">
    <a href="{{ route('attendance.index') }}" class="text-sm text-gray-600 hover:text-gray-800">
        <i class="fas fa-arrow-left mr-1"></i> Back to Attendance
    </a>
</div>
@endsection
