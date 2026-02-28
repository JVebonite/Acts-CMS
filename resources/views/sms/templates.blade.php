@extends('layouts.app')

@section('title', 'SMS Templates - ACTS Church CMS')
@section('page-title', 'SMS Templates')
@section('page-description', 'Manage reusable SMS templates')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-5 border-b border-gray-100">
                <h3 class="text-base font-semibold text-navy-800">All Templates</h3>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($templates as $tpl)
                <div class="p-5 hover:bg-gray-50/50">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="text-sm font-semibold text-navy-800">{{ $tpl->name }}</h4>
                        <div class="flex items-center space-x-2">
                            @if($tpl->category)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-blue-100 text-blue-700">{{ $tpl->category }}</span>
                            @endif
                            <form method="POST" action="{{ route('sms.templates.destroy', $tpl) }}" class="inline" onsubmit="return confirm('Delete this template?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1 text-gray-400 hover:text-red-600"><i class="fas fa-trash text-xs"></i></button>
                            </form>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 bg-gray-50 rounded-lg p-3">{{ $tpl->content }}</p>
                    <p class="text-xs text-gray-400 mt-2">{{ strlen($tpl->content) }}/160 chars</p>
                </div>
                @empty
                <div class="p-8 text-center">
                    <i class="fas fa-file-alt text-4xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500 text-sm">No templates yet.</p>
                </div>
                @endforelse
            </div>
            @if($templates->hasPages())
            <div class="px-5 py-3 border-t border-gray-100">{{ $templates->links() }}</div>
            @endif
        </div>
    </div>

    <div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-sm font-semibold text-navy-800 mb-4"><i class="fas fa-plus-circle mr-2 text-gold-500"></i>Create Template</h3>
            <form method="POST" action="{{ route('sms.templates.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <input type="text" name="category" placeholder="e.g., Birthday, Reminder"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Content <span class="text-red-500">*</span></label>
                    <textarea name="content" rows="4" required maxlength="160"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500"></textarea>
                </div>
                <button type="submit" class="w-full px-4 py-2 gradient-gold text-white text-sm font-medium rounded-lg hover:opacity-90">
                    <i class="fas fa-save mr-1"></i> Save Template
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
