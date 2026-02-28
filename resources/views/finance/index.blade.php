@extends('layouts.app')

@section('title', 'Finance - ACTS Church CMS')
@section('page-title', 'Finance')
@section('page-description', 'Financial overview and management')

@section('content')
{{-- Summary Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="stat-card bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Donations</p>
                <p class="text-2xl font-bold text-navy-800 mt-1">{{ number_format($totalDonations, 2) }}</p>
            </div>
            <div class="w-10 h-10 rounded-lg bg-green-50 flex items-center justify-center">
                <i class="fas fa-hand-holding-usd text-green-600"></i>
            </div>
        </div>
    </div>
    <div class="stat-card bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Expenses</p>
                <p class="text-2xl font-bold text-navy-800 mt-1">{{ number_format($totalExpenses, 2) }}</p>
            </div>
            <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center">
                <i class="fas fa-receipt text-red-500"></i>
            </div>
        </div>
    </div>
    <div class="stat-card bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">This Month Income</p>
                <p class="text-2xl font-bold text-green-600 mt-1">{{ number_format($monthlyDonations, 2) }}</p>
            </div>
            <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center">
                <i class="fas fa-arrow-up text-emerald-600"></i>
            </div>
        </div>
    </div>
    <div class="stat-card bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">This Month Expenses</p>
                <p class="text-2xl font-bold text-red-500 mt-1">{{ number_format($monthlyExpenses, 2) }}</p>
            </div>
            <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center">
                <i class="fas fa-arrow-down text-red-500"></i>
            </div>
        </div>
    </div>
</div>

{{-- Quick Links --}}
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
    <a href="{{ route('finance.donations') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center hover:border-gold-300 transition-colors">
        <i class="fas fa-hand-holding-usd text-gold-500 text-xl mb-2"></i>
        <p class="text-xs font-medium text-navy-800">Donations</p>
    </a>
    <a href="{{ route('finance.expenses') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center hover:border-gold-300 transition-colors">
        <i class="fas fa-file-invoice-dollar text-gold-500 text-xl mb-2"></i>
        <p class="text-xs font-medium text-navy-800">Expenses</p>
    </a>
    <a href="{{ route('finance.pledges') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center hover:border-gold-300 transition-colors">
        <i class="fas fa-handshake text-gold-500 text-xl mb-2"></i>
        <p class="text-xs font-medium text-navy-800">Pledges</p>
    </a>
    <a href="{{ route('finance.campaigns') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center hover:border-gold-300 transition-colors">
        <i class="fas fa-bullhorn text-gold-500 text-xl mb-2"></i>
        <p class="text-xs font-medium text-navy-800">Campaigns</p>
    </a>
    <a href="{{ route('finance.expense-categories') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center hover:border-gold-300 transition-colors">
        <i class="fas fa-tags text-gold-500 text-xl mb-2"></i>
        <p class="text-xs font-medium text-navy-800">Categories</p>
    </a>
    <a href="{{ route('reports.finance') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center hover:border-gold-300 transition-colors">
        <i class="fas fa-chart-pie text-gold-500 text-xl mb-2"></i>
        <p class="text-xs font-medium text-navy-800">Reports</p>
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Recent Donations --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-navy-800"><i class="fas fa-hand-holding-usd mr-2 text-gold-500"></i>Recent Donations</h3>
            <a href="{{ route('finance.donations') }}" class="text-xs text-gold-600 hover:text-gold-700">View All</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($recentDonations as $d)
            <div class="px-5 py-3 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-900">{{ $d->member ? $d->member->full_name : 'Anonymous' }}</p>
                    <p class="text-xs text-gray-500">{{ ucfirst($d->donation_type) }} &middot; {{ $d->donation_date->format('M d') }}</p>
                </div>
                <span class="text-sm font-semibold text-green-700">{{ number_format($d->amount, 2) }}</span>
            </div>
            @empty
            <p class="px-5 py-8 text-center text-sm text-gray-500">No donations yet.</p>
            @endforelse
        </div>
    </div>

    {{-- Recent Expenses --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-navy-800"><i class="fas fa-receipt mr-2 text-gold-500"></i>Recent Expenses</h3>
            <a href="{{ route('finance.expenses') }}" class="text-xs text-gold-600 hover:text-gold-700">View All</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($recentExpenses as $e)
            <div class="px-5 py-3 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-900">{{ $e->description ?: ($e->category ? $e->category->name : 'Expense') }}</p>
                    <p class="text-xs text-gray-500">{{ $e->category ? $e->category->name : '-' }} &middot; {{ $e->expense_date->format('M d') }}</p>
                </div>
                <div class="text-right">
                    <span class="text-sm font-semibold text-red-600">{{ number_format($e->amount, 2) }}</span>
                    <p class="text-xs {{ $e->approval_status === 'approved' ? 'text-green-600' : ($e->approval_status === 'rejected' ? 'text-red-500' : 'text-amber-600') }}">
                        {{ ucfirst($e->approval_status) }}
                    </p>
                </div>
            </div>
            @empty
            <p class="px-5 py-8 text-center text-sm text-gray-500">No expenses yet.</p>
            @endforelse
        </div>
    </div>
</div>

{{-- Active Campaigns --}}
@if($activeCampaigns->count())
<div class="bg-white rounded-xl shadow-sm border border-gray-100 mt-6">
    <div class="p-5 border-b border-gray-100">
        <h3 class="text-sm font-semibold text-navy-800"><i class="fas fa-bullhorn mr-2 text-gold-500"></i>Active Campaigns</h3>
    </div>
    <div class="p-5 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($activeCampaigns as $campaign)
        <div class="border border-gray-200 rounded-lg p-4">
            <h4 class="text-sm font-semibold text-navy-800">{{ $campaign->name }}</h4>
            <p class="text-xs text-gray-500 mt-1">Target: {{ number_format($campaign->target_amount, 2) }}</p>
            <div class="mt-3">
                <div class="flex items-center justify-between text-xs mb-1">
                    <span class="text-gray-500">Progress</span>
                    <span class="font-medium text-navy-800">{{ $campaign->progress_percentage }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="gradient-gold h-2 rounded-full" style="width: {{ min(100, $campaign->progress_percentage) }}%"></div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
@endsection
