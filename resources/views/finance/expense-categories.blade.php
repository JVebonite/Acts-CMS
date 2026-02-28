@extends('layouts.app')

@section('title', 'Expense Categories - ACTS Church CMS')
@section('page-title', 'Expense Categories')
@section('page-description', 'Manage expense categories and budgets')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-5 border-b border-gray-100">
                <h3 class="text-base font-semibold text-navy-800">Categories</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Name</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Description</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Budget</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Spent</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($categories as $cat)
                        <tr>
                            <td class="px-5 py-3 text-sm font-medium text-gray-900">{{ $cat->name }}</td>
                            <td class="px-5 py-3 text-sm text-gray-600">{{ $cat->description ?: '-' }}</td>
                            <td class="px-5 py-3 text-right text-sm font-medium text-navy-800">{{ number_format($cat->budget_amount, 2) }}</td>
                            <td class="px-5 py-3 text-right text-sm font-medium text-red-600">{{ number_format($cat->expenses_sum_amount ?? 0, 2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-5 py-8 text-center text-gray-500 text-sm">No categories yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-sm font-semibold text-navy-800 mb-4"><i class="fas fa-plus-circle mr-2 text-gold-500"></i>Add Category</h3>
            <form method="POST" action="{{ route('finance.expense-categories.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Budget Amount</label>
                    <input type="number" name="budget_amount" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                </div>
                <button type="submit" class="w-full px-4 py-2 gradient-gold text-white text-sm font-medium rounded-lg hover:opacity-90">
                    <i class="fas fa-save mr-1"></i> Add Category
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
