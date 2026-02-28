@extends('layouts.app')

@section('title', 'Pledges - ACTS Church CMS')
@section('page-title', 'Pledges')
@section('page-description', 'Manage pledge records')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h3 class="text-base font-semibold text-navy-800">Pledge Records</h3>
        <button onclick="document.getElementById('pledgeModal').classList.remove('hidden')"
                class="inline-flex items-center px-4 py-2 gradient-gold text-white text-sm font-medium rounded-lg hover:opacity-90 shadow-sm">
            <i class="fas fa-plus mr-2"></i> Record Pledge
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Member</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Campaign</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Pledged</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Fulfilled</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Progress</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($pledges as $pledge)
                <tr class="hover:bg-gray-50/50">
                    <td class="px-5 py-3 text-sm font-medium text-gray-900">{{ $pledge->member->full_name }}</td>
                    <td class="px-5 py-3 text-sm text-gray-600">{{ $pledge->campaign ? $pledge->campaign->name : '-' }}</td>
                    <td class="px-5 py-3 text-sm font-semibold text-navy-800">{{ number_format($pledge->amount_pledged, 2) }}</td>
                    <td class="px-5 py-3 text-sm text-green-700">{{ number_format($pledge->amount_fulfilled, 2) }}</td>
                    <td class="px-5 py-3">
                        <div class="flex items-center space-x-2">
                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                <div class="gradient-gold h-2 rounded-full" style="width: {{ $pledge->fulfillment_percentage }}%"></div>
                            </div>
                            <span class="text-xs font-medium text-gray-600">{{ $pledge->fulfillment_percentage }}%</span>
                        </div>
                    </td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $pledge->status === 'fulfilled' ? 'bg-green-100 text-green-700' :
                               ($pledge->status === 'active' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600') }}">
                            {{ ucfirst($pledge->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-12 text-center text-gray-500 text-sm">No pledges found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($pledges->hasPages())
    <div class="px-5 py-3 border-t border-gray-100">{{ $pledges->withQueryString()->links() }}</div>
    @endif
</div>

<div id="pledgeModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-navy-800">Record Pledge</h3>
            <button onclick="document.getElementById('pledgeModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('finance.pledges.store') }}" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Member <span class="text-red-500">*</span></label>
                <select name="member_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                    <option value="">Select Member</option>
                    @foreach($members as $m)<option value="{{ $m->id }}">{{ $m->full_name }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Campaign</label>
                <select name="campaign_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                    <option value="">None</option>
                    @foreach($campaigns as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Amount Pledged <span class="text-red-500">*</span></label>
                    <input type="number" name="amount_pledged" step="0.01" min="0.01" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Frequency <span class="text-red-500">*</span></label>
                    <select name="frequency" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                        <option value="one_time">One Time</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                        <option value="quarterly">Quarterly</option>
                        <option value="annually">Annually</option>
                    </select>
                </div>
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
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea name="notes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500"></textarea>
            </div>
            <div class="flex justify-end space-x-3 pt-2">
                <button type="button" onclick="document.getElementById('pledgeModal').classList.add('hidden')" class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-lg">Cancel</button>
                <button type="submit" class="px-4 py-2 gradient-gold text-white text-sm font-medium rounded-lg hover:opacity-90"><i class="fas fa-save mr-1"></i> Save</button>
            </div>
        </form>
    </div>
</div>
@endsection
