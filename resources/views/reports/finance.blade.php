@extends('layouts.app')

@section('title', 'Finance Report - ACTS Church CMS')
@section('page-title', 'Finance Report')
@section('page-description', 'Financial analysis and trends')

@section('content')
<div class="mb-6">
    <form method="GET" action="{{ route('reports.finance') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
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
        <p class="text-3xl font-bold text-green-600">{{ number_format($totalDonations, 2) }}</p>
        <p class="text-xs text-gray-500 mt-1">Total Income</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 text-center">
        <p class="text-3xl font-bold text-red-500">{{ number_format($totalExpenses, 2) }}</p>
        <p class="text-xs text-gray-500 mt-1">Total Expenses</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 text-center">
        @php $net = $totalDonations - $totalExpenses; @endphp
        <p class="text-3xl font-bold {{ $net >= 0 ? 'text-green-600' : 'text-red-500' }}">{{ number_format(abs($net), 2) }}</p>
        <p class="text-xs text-gray-500 mt-1">{{ $net >= 0 ? 'Net Surplus' : 'Net Deficit' }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h3 class="text-sm font-semibold text-navy-800 mb-4">Donations by Type</h3>
        <canvas id="donationTypeChart" height="200"></canvas>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h3 class="text-sm font-semibold text-navy-800 mb-4">Expenses by Category</h3>
        <canvas id="expenseCatChart" height="200"></canvas>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-6">
    <h3 class="text-sm font-semibold text-navy-800 mb-4">Monthly Donation Trend</h3>
    <canvas id="monthlyChart" height="150"></canvas>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-5 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-navy-800">Donation Breakdown</h3>
        </div>
        <div class="divide-y divide-gray-50">
            @foreach($donationsByType as $d)
            <div class="px-5 py-3 flex items-center justify-between">
                <span class="text-sm text-gray-700">{{ ucfirst($d->donation_type) }}</span>
                <span class="text-sm font-semibold text-green-700">{{ number_format($d->total, 2) }}</span>
            </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-5 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-navy-800">Expense Breakdown</h3>
        </div>
        <div class="divide-y divide-gray-50">
            @foreach($expensesByCategory as $e)
            <div class="px-5 py-3 flex items-center justify-between">
                <span class="text-sm text-gray-700">{{ $e->name }}</span>
                <span class="text-sm font-semibold text-red-600">{{ number_format($e->total, 2) }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
const colors = ['#d4a017','#1e3a7b','#22c55e','#ef4444','#8b5cf6','#ec4899','#f59e0b'];

new Chart(document.getElementById('donationTypeChart'), {
    type: 'doughnut',
    data: {
        labels: @json($donationsByType->pluck('donation_type')->map(fn($t) => ucfirst($t))),
        datasets: [{ data: @json($donationsByType->pluck('total')), backgroundColor: colors }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});

new Chart(document.getElementById('expenseCatChart'), {
    type: 'doughnut',
    data: {
        labels: @json($expensesByCategory->pluck('name')),
        datasets: [{ data: @json($expensesByCategory->pluck('total')), backgroundColor: colors }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});

const trend = @json($monthlyTrend);
new Chart(document.getElementById('monthlyChart'), {
    type: 'line',
    data: {
        labels: trend.map(d => months[d.month - 1]),
        datasets: [{
            label: 'Donations',
            data: trend.map(d => parseFloat(d.total)),
            borderColor: '#d4a017',
            backgroundColor: 'rgba(212,160,23,0.1)',
            fill: true, tension: 0.4,
        }]
    },
    options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
});
</script>
@endpush
