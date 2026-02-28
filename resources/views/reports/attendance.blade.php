@extends('layouts.app')

@section('title', 'Attendance Report - ACTS Church CMS')
@section('page-title', 'Attendance Report')
@section('page-description', 'Service attendance analysis')

@section('content')
<div class="mb-6">
    <form method="GET" action="{{ route('reports.attendance') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Date From</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Date To</label>
                <input type="date" name="date_to" value="{{ $dateTo }}" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
            </div>
            <button type="submit" class="px-4 py-2 gradient-gold text-white text-sm rounded-lg hover:opacity-90"><i class="fas fa-filter mr-1"></i> Generate</button>
        </div>
    </form>
</div>

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 text-center">
        <p class="text-3xl font-bold text-navy-800">{{ $attendances->count() }}</p>
        <p class="text-xs text-gray-500 mt-1">Total Records</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 text-center">
        <p class="text-3xl font-bold text-navy-800">{{ $dailySummary->count() }}</p>
        <p class="text-xs text-gray-500 mt-1">Service Days</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 text-center">
        <p class="text-3xl font-bold text-navy-800">{{ $dailySummary->count() > 0 ? round($attendances->count() / $dailySummary->count()) : 0 }}</p>
        <p class="text-xs text-gray-500 mt-1">Avg per Service</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h3 class="text-sm font-semibold text-navy-800 mb-4">Daily Attendance</h3>
        <canvas id="dailyChart" height="200"></canvas>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-5 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-navy-800">Top Attendees</h3>
        </div>
        <div class="divide-y divide-gray-50 max-h-80 overflow-y-auto">
            @foreach($memberAttendance->take(15) as $ma)
            <div class="px-5 py-2.5 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-7 h-7 rounded-full gradient-navy flex items-center justify-center flex-shrink-0">
                        <span class="text-white text-[10px] font-bold">{{ substr($ma['member']->first_name ?? '', 0, 1) }}</span>
                    </div>
                    <span class="text-sm text-gray-900">{{ $ma['member']->full_name ?? 'Unknown' }}</span>
                </div>
                <span class="text-sm font-semibold text-navy-800">{{ $ma['count'] }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const labels = @json($dailySummary->keys()->toArray());
const values = @json($dailySummary->values()->toArray());
new Chart(document.getElementById('dailyChart'), {
    type: 'bar',
    data: {
        labels: labels.map(d => new Date(d).toLocaleDateString('en', {month:'short', day:'numeric'})),
        datasets: [{
            label: 'Attendees',
            data: values,
            backgroundColor: 'rgba(30, 58, 123, 0.8)',
            borderRadius: 6,
        }]
    },
    options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
});
</script>
@endpush
