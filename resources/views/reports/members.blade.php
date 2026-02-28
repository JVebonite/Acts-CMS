@extends('layouts.app')

@section('title', 'Member Report - ACTS Church CMS')
@section('page-title', 'Member Report')
@section('page-description', 'Membership statistics and demographics')

@section('content')
<div class="mb-6">
    <form method="GET" action="{{ route('reports.members') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                    <option value="">All</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Joined From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Joined To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
            </div>
            <button type="submit" class="px-4 py-2 gradient-gold text-white text-sm rounded-lg hover:opacity-90"><i class="fas fa-filter mr-1"></i> Generate</button>
        </div>
    </form>
</div>

<div class="grid grid-cols-2 sm:grid-cols-5 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
        <p class="text-2xl font-bold text-navy-800">{{ $summary['total'] }}</p>
        <p class="text-xs text-gray-500">Total</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
        <p class="text-2xl font-bold text-green-600">{{ $summary['active'] }}</p>
        <p class="text-xs text-gray-500">Active</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
        <p class="text-2xl font-bold text-red-500">{{ $summary['inactive'] }}</p>
        <p class="text-xs text-gray-500">Inactive</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
        <p class="text-2xl font-bold text-blue-600">{{ $summary['male'] }}</p>
        <p class="text-xs text-gray-500">Male</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
        <p class="text-2xl font-bold text-pink-500">{{ $summary['female'] }}</p>
        <p class="text-xs text-gray-500">Female</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h3 class="text-sm font-semibold text-navy-800 mb-4">Membership Status</h3>
        <canvas id="statusChart" height="200"></canvas>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h3 class="text-sm font-semibold text-navy-800 mb-4">Gender Distribution</h3>
        <canvas id="genderChart" height="200"></canvas>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="p-5 border-b border-gray-100">
        <h3 class="text-sm font-semibold text-navy-800">Member List ({{ $members->count() }})</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Name</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Gender</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Phone</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Joined</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($members as $m)
                <tr>
                    <td class="px-5 py-3 text-sm font-medium text-gray-900">{{ $m->full_name }}</td>
                    <td class="px-5 py-3 text-sm text-gray-600">{{ $m->gender ? ucfirst($m->gender) : '-' }}</td>
                    <td class="px-5 py-3"><span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $m->membership_status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">{{ ucfirst($m->membership_status) }}</span></td>
                    <td class="px-5 py-3 text-sm text-gray-600">{{ $m->phone ?: '-' }}</td>
                    <td class="px-5 py-3 text-sm text-gray-600">{{ $m->membership_date ? $m->membership_date->format('M d, Y') : '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: ['Active', 'Inactive'],
        datasets: [{
            data: [{{ $summary['active'] }}, {{ $summary['inactive'] }}],
            backgroundColor: ['#22c55e', '#ef4444'],
        }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});
new Chart(document.getElementById('genderChart'), {
    type: 'doughnut',
    data: {
        labels: ['Male', 'Female'],
        datasets: [{
            data: [{{ $summary['male'] }}, {{ $summary['female'] }}],
            backgroundColor: ['#3b82f6', '#ec4899'],
        }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});
</script>
@endpush
