@extends('layouts.app')

@section('title', 'Donations - ACTS Church CMS')
@section('page-title', 'Donations')
@section('page-description', 'Manage donation records')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h3 class="text-base font-semibold text-navy-800">Donation Records</h3>
        <button onclick="document.getElementById('donationModal').classList.remove('hidden')"
                class="inline-flex items-center px-4 py-2 gradient-gold text-white text-sm font-medium rounded-lg hover:opacity-90 shadow-sm">
            <i class="fas fa-plus mr-2"></i> Record Donation
        </button>
    </div>

    <div class="p-4 border-b border-gray-50 bg-gray-50/50">
        <form method="GET" action="{{ route('finance.donations') }}" class="flex flex-wrap items-center gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by member name..."
                   class="flex-1 min-w-[150px] px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
            <select name="type" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                <option value="">All Types</option>
                <option value="tithe" {{ request('type') === 'tithe' ? 'selected' : '' }}>Tithe</option>
                <option value="offering" {{ request('type') === 'offering' ? 'selected' : '' }}>Offering</option>
                <option value="special" {{ request('type') === 'special' ? 'selected' : '' }}>Special</option>
                <option value="mission" {{ request('type') === 'mission' ? 'selected' : '' }}>Mission</option>
                <option value="building" {{ request('type') === 'building' ? 'selected' : '' }}>Building</option>
            </select>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
            <button type="submit" class="px-4 py-2 bg-navy-600 text-white text-sm rounded-lg hover:bg-navy-700"><i class="fas fa-search mr-1"></i> Filter</button>
            <a href="{{ route('finance.donations') }}" class="px-4 py-2 text-gray-600 text-sm">Reset</a>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Member</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Type</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Method</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Campaign</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Amount</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($donations as $donation)
                <tr class="hover:bg-gray-50/50">
                    <td class="px-5 py-3 text-sm font-medium text-gray-900">{{ $donation->member ? $donation->member->full_name : 'Anonymous' }}</td>
                    <td class="px-5 py-3 text-sm text-gray-600">{{ $donation->donation_date->format('M d, Y') }}</td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                            {{ ucfirst($donation->donation_type) }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-sm text-gray-600">{{ ucfirst(str_replace('_', ' ', $donation->payment_method)) }}</td>
                    <td class="px-5 py-3 text-sm text-gray-600">{{ $donation->campaign ? $donation->campaign->name : '-' }}</td>
                    <td class="px-5 py-3 text-right text-sm font-semibold text-green-700">{{ number_format($donation->amount, 2) }}</td>
                    <td class="px-5 py-3 text-right">
                        <form method="POST" action="{{ route('finance.donations.destroy', $donation) }}" class="inline" onsubmit="return confirm('Delete this donation?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600"><i class="fas fa-trash text-sm"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-12 text-center text-gray-500 text-sm">No donations found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($donations->hasPages())
    <div class="px-5 py-3 border-t border-gray-100">{{ $donations->withQueryString()->links() }}</div>
    @endif
</div>

{{-- Donation Modal --}}
<div id="donationModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-navy-800">Record Donation</h3>
            <button onclick="document.getElementById('donationModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('finance.donations.store') }}" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Member</label>
                <select name="member_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                    <option value="">Anonymous</option>
                    @foreach($members as $member)
                    <option value="{{ $member->id }}">{{ $member->full_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Amount <span class="text-red-500">*</span></label>
                    <input type="number" name="amount" step="0.01" min="0.01" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date <span class="text-red-500">*</span></label>
                    <input type="date" name="donation_date" value="{{ now()->toDateString() }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type <span class="text-red-500">*</span></label>
                    <select name="donation_type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                        <option value="tithe">Tithe</option>
                        <option value="offering">Offering</option>
                        <option value="special">Special</option>
                        <option value="mission">Mission</option>
                        <option value="building">Building</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method <span class="text-red-500">*</span></label>
                    <select name="payment_method" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                        <option value="cash">Cash</option>
                        <option value="cheque">Cheque</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="mobile_money">Mobile Money</option>
                        <option value="card">Card</option>
                        <option value="online">Online</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Campaign</label>
                <select name="campaign_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                    <option value="">None</option>
                    @foreach($campaigns as $campaign)
                    <option value="{{ $campaign->id }}">{{ $campaign->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Receipt Number</label>
                <input type="text" name="receipt_number" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea name="notes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500"></textarea>
            </div>
            <div class="flex justify-end space-x-3 pt-2">
                <button type="button" onclick="document.getElementById('donationModal').classList.add('hidden')"
                        class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-lg">Cancel</button>
                <button type="submit" class="px-4 py-2 gradient-gold text-white text-sm font-medium rounded-lg hover:opacity-90">
                    <i class="fas fa-save mr-1"></i> Save Donation
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
