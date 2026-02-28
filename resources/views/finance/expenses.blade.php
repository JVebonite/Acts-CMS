@extends('layouts.app')

@section('title', 'Expenses - ACTS Church CMS')
@section('page-title', 'Expenses')
@section('page-description', 'Manage expense records')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h3 class="text-base font-semibold text-navy-800">Expense Records</h3>
        <button onclick="document.getElementById('expenseModal').classList.remove('hidden')"
                class="inline-flex items-center px-4 py-2 gradient-gold text-white text-sm font-medium rounded-lg hover:opacity-90 shadow-sm">
            <i class="fas fa-plus mr-2"></i> Record Expense
        </button>
    </div>

    <div class="p-4 border-b border-gray-50 bg-gray-50/50">
        <form method="GET" action="{{ route('finance.expenses') }}" class="flex flex-wrap items-center gap-3">
            <select name="category_id" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-navy-600 text-white text-sm rounded-lg hover:bg-navy-700"><i class="fas fa-search mr-1"></i> Filter</button>
            <a href="{{ route('finance.expenses') }}" class="px-4 py-2 text-gray-600 text-sm">Reset</a>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Description</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Category</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Amount</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($expenses as $expense)
                <tr class="hover:bg-gray-50/50">
                    <td class="px-5 py-3">
                        <p class="text-sm font-medium text-gray-900">{{ $expense->description ?: 'No description' }}</p>
                        <p class="text-xs text-gray-500">{{ $expense->vendor ?: '' }} {{ $expense->department ? '· ' . $expense->department : '' }}</p>
                    </td>
                    <td class="px-5 py-3 text-sm text-gray-600">{{ $expense->category ? $expense->category->name : '-' }}</td>
                    <td class="px-5 py-3 text-sm text-gray-600">{{ $expense->expense_date->format('M d, Y') }}</td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $expense->approval_status === 'approved' ? 'bg-green-100 text-green-700' :
                               ($expense->approval_status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                            {{ ucfirst($expense->approval_status) }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-right text-sm font-semibold text-red-600">{{ number_format($expense->amount, 2) }}</td>
                    <td class="px-5 py-3 text-right">
                        @if($expense->approval_status === 'pending')
                        <form method="POST" action="{{ route('finance.expenses.approve', $expense) }}" class="inline">
                            @csrf
                            <button type="submit" class="p-1.5 text-gray-400 hover:text-green-600" title="Approve"><i class="fas fa-check text-sm"></i></button>
                        </form>
                        <form method="POST" action="{{ route('finance.expenses.reject', $expense) }}" class="inline">
                            @csrf
                            <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600" title="Reject"><i class="fas fa-times text-sm"></i></button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-12 text-center text-gray-500 text-sm">No expenses found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($expenses->hasPages())
    <div class="px-5 py-3 border-t border-gray-100">{{ $expenses->withQueryString()->links() }}</div>
    @endif
</div>

{{-- Expense Modal --}}
<div id="expenseModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-navy-800">Record Expense</h3>
            <button onclick="document.getElementById('expenseModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('finance.expenses.store') }}" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Amount <span class="text-red-500">*</span></label>
                    <input type="number" name="amount" step="0.01" min="0.01" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date <span class="text-red-500">*</span></label>
                    <input type="date" name="expense_date" value="{{ now()->toDateString() }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <select name="category_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                    <option value="">Select Category</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Vendor</label>
                    <input type="text" name="vendor" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                    <input type="text" name="department" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Receipt</label>
                <input type="file" name="receipt_path" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:bg-gold-50 file:text-gold-700">
            </div>
            <div class="flex justify-end space-x-3 pt-2">
                <button type="button" onclick="document.getElementById('expenseModal').classList.add('hidden')" class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-lg">Cancel</button>
                <button type="submit" class="px-4 py-2 gradient-gold text-white text-sm font-medium rounded-lg hover:opacity-90"><i class="fas fa-save mr-1"></i> Save</button>
            </div>
        </form>
    </div>
</div>
@endsection
