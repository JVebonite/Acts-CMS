@extends('layouts.app')

@section('title', 'Bulk SMS - ACTS Church CMS')
@section('page-title', 'Bulk SMS')
@section('page-description', 'Manage SMS messages and templates')

@section('content')
<div class="flex flex-wrap gap-3 mb-6">
    <a href="{{ route('sms.create') }}" class="inline-flex items-center px-4 py-2 gradient-gold text-white text-sm font-medium rounded-lg hover:opacity-90 shadow-sm">
        <i class="fas fa-paper-plane mr-2"></i> Compose SMS
    </a>
    <a href="{{ route('sms.templates') }}" class="inline-flex items-center px-4 py-2 bg-white text-navy-700 text-sm font-medium rounded-lg border border-gray-200 hover:bg-gray-50">
        <i class="fas fa-file-alt mr-2"></i> Templates
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="p-5 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-base font-semibold text-navy-800">Message History</h3>
        <form method="GET" class="flex items-center gap-2">
            <select name="status" onchange="this.form.submit()" class="px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                <option value="">All</option>
                <option value="sent" {{ request('status') === 'sent' ? 'selected' : '' }}>Sent</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
            </select>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Message</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Type</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Recipients</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($messages as $msg)
                <tr class="hover:bg-gray-50/50">
                    <td class="px-5 py-3">
                        <p class="text-sm text-gray-900 truncate max-w-xs">{{ Str::limit($msg->message, 60) }}</p>
                    </td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                            {{ ucfirst($msg->recipient_type) }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-sm text-gray-600">{{ $msg->total_recipients }}</td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $msg->status === 'sent' ? 'bg-green-100 text-green-700' :
                               ($msg->status === 'failed' ? 'bg-red-100 text-red-700' :
                               ($msg->status === 'scheduled' ? 'bg-purple-100 text-purple-700' : 'bg-amber-100 text-amber-700')) }}">
                            {{ ucfirst($msg->status) }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-sm text-gray-600">{{ $msg->created_at->format('M d, Y H:i') }}</td>
                    <td class="px-5 py-3 text-right">
                        <a href="{{ route('sms.show', $msg) }}" class="p-1.5 text-gray-400 hover:text-navy-600"><i class="fas fa-eye text-sm"></i></a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-12 text-center">
                        <i class="fas fa-comment-sms text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500 text-sm">No messages sent yet.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($messages->hasPages())
    <div class="px-5 py-3 border-t border-gray-100">{{ $messages->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
