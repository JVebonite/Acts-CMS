@extends('layouts.app')

@section('title', 'Campaigns - ACTS Church CMS')
@section('page-title', 'Campaigns')
@section('page-description', 'Manage fundraising campaigns')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h3 class="text-base font-semibold text-navy-800">All Campaigns</h3>
        <button onclick="document.getElementById('campaignModal').classList.remove('hidden')"
                class="inline-flex items-center px-4 py-2 gradient-gold text-white text-sm font-medium rounded-lg hover:opacity-90 shadow-sm">
            <i class="fas fa-plus mr-2"></i> New Campaign
        </button>
    </div>
    <div class="p-5 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($campaigns as $campaign)
        <div class="border border-gray-200 rounded-xl p-5 hover:border-gold-300 transition-colors">
            <div class="flex items-center justify-between mb-2">
                <h4 class="text-sm font-semibold text-navy-800">{{ $campaign->name }}</h4>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium
                    {{ $campaign->status === 'active' ? 'bg-green-100 text-green-700' :
                       ($campaign->status === 'completed' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600') }}">
                    {{ ucfirst($campaign->status) }}
                </span>
            </div>
            @if($campaign->description)
            <p class="text-xs text-gray-500 mb-3">{{ Str::limit($campaign->description, 80) }}</p>
            @endif
            <div class="space-y-2">
                <div class="flex justify-between text-xs">
                    <span class="text-gray-500">Target</span>
                    <span class="font-medium text-navy-800">{{ number_format($campaign->target_amount, 2) }}</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-gray-500">Raised</span>
                    <span class="font-medium text-green-700">{{ number_format($campaign->donations_sum_amount ?? 0, 2) }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    @php $pct = $campaign->target_amount > 0 ? min(100, round((($campaign->donations_sum_amount ?? 0) / $campaign->target_amount) * 100)) : 0; @endphp
                    <div class="gradient-gold h-2 rounded-full" style="width: {{ $pct }}%"></div>
                </div>
                <p class="text-right text-xs text-gray-500">{{ $pct }}% &middot; {{ $campaign->donations_count ?? 0 }} donations</p>
            </div>
            @if($campaign->start_date || $campaign->end_date)
            <div class="mt-3 pt-3 border-t border-gray-100 text-xs text-gray-500">
                {{ $campaign->start_date ? $campaign->start_date->format('M d, Y') : '?' }} - {{ $campaign->end_date ? $campaign->end_date->format('M d, Y') : 'Ongoing' }}
            </div>
            @endif
        </div>
        @empty
        <div class="col-span-full text-center py-12">
            <i class="fas fa-bullhorn text-4xl text-gray-300 mb-3"></i>
            <p class="text-gray-500 text-sm">No campaigns yet.</p>
        </div>
        @endforelse
    </div>
    @if($campaigns->hasPages())
    <div class="px-5 py-3 border-t border-gray-100">{{ $campaigns->withQueryString()->links() }}</div>
    @endif
</div>

<div id="campaignModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-navy-800">New Campaign</h3>
            <button onclick="document.getElementById('campaignModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('finance.campaigns.store') }}" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Campaign Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Target Amount <span class="text-red-500">*</span></label>
                <input type="number" name="target_amount" step="0.01" min="0" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                    <input type="date" name="start_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                    <input type="date" name="end_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                </div>
            </div>
            <div class="flex justify-end space-x-3 pt-2">
                <button type="button" onclick="document.getElementById('campaignModal').classList.add('hidden')" class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-lg">Cancel</button>
                <button type="submit" class="px-4 py-2 gradient-gold text-white text-sm font-medium rounded-lg hover:opacity-90"><i class="fas fa-save mr-1"></i> Create</button>
            </div>
        </form>
    </div>
</div>
@endsection
