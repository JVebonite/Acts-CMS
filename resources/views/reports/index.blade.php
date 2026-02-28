@extends('layouts.app')

@section('title', 'Reports - ACTS Church CMS')
@section('page-title', 'Reports')
@section('page-description', 'Generate and view church reports')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <a href="{{ route('reports.members') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:border-gold-300 hover:shadow-md transition-all group">
        <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center mb-4 group-hover:bg-blue-100 transition-colors">
            <i class="fas fa-users text-navy-600 text-xl"></i>
        </div>
        <h3 class="text-base font-semibold text-navy-800 mb-1">Member Report</h3>
        <p class="text-sm text-gray-500">Overview of membership demographics, status breakdown, and growth trends.</p>
        <div class="mt-4 flex items-center text-gold-600 text-sm font-medium">
            Generate Report <i class="fas fa-arrow-right ml-2 text-xs"></i>
        </div>
    </a>

    <a href="{{ route('reports.attendance') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:border-gold-300 hover:shadow-md transition-all group">
        <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center mb-4 group-hover:bg-green-100 transition-colors">
            <i class="fas fa-clipboard-check text-green-600 text-xl"></i>
        </div>
        <h3 class="text-base font-semibold text-navy-800 mb-1">Attendance Report</h3>
        <p class="text-sm text-gray-500">Service attendance trends, daily summaries, and member participation rates.</p>
        <div class="mt-4 flex items-center text-gold-600 text-sm font-medium">
            Generate Report <i class="fas fa-arrow-right ml-2 text-xs"></i>
        </div>
    </a>

    <a href="{{ route('reports.finance') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:border-gold-300 hover:shadow-md transition-all group">
        <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center mb-4 group-hover:bg-emerald-100 transition-colors">
            <i class="fas fa-chart-pie text-emerald-600 text-xl"></i>
        </div>
        <h3 class="text-base font-semibold text-navy-800 mb-1">Finance Report</h3>
        <p class="text-sm text-gray-500">Income vs expenses, donation breakdown by type, and monthly financial trends.</p>
        <div class="mt-4 flex items-center text-gold-600 text-sm font-medium">
            Generate Report <i class="fas fa-arrow-right ml-2 text-xs"></i>
        </div>
    </a>
</div>
@endsection
