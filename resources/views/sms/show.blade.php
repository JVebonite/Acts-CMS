@extends('layouts.app')

@section('title', 'SMS Details - ACTS Church CMS')
@section('page-title', 'SMS Details')
@section('page-description', 'Message details and delivery report')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                {{ $smsMessage->status === 'sent' ? 'bg-green-100 text-green-700' :
                   ($smsMessage->status === 'failed' ? 'bg-red-100 text-red-700' :
                   ($smsMessage->status === 'scheduled' ? 'bg-purple-100 text-purple-700' : 'bg-amber-100 text-amber-700')) }}">
                {{ ucfirst($smsMessage->status) }}
            </span>
            <span class="text-sm text-gray-500">{{ $smsMessage->created_at->format('M d, Y H:i') }}</span>
        </div>

        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <p class="text-sm text-gray-800">{{ $smsMessage->message }}</p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
            <div class="text-center">
                <p class="text-2xl font-bold text-navy-800">{{ $smsMessage->total_recipients }}</p>
                <p class="text-xs text-gray-500">Total</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-green-600">{{ $smsMessage->delivered_count }}</p>
                <p class="text-xs text-gray-500">Delivered</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-red-500">{{ $smsMessage->failed_count }}</p>
                <p class="text-xs text-gray-500">Failed</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-navy-800">{{ ucfirst($smsMessage->recipient_type) }}</p>
                <p class="text-xs text-gray-500">Type</p>
            </div>
        </div>

        <div class="space-y-3 border-t border-gray-100 pt-4">
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Sent By</span>
                <span class="text-gray-800">{{ $smsMessage->sender ? $smsMessage->sender->name : '-' }}</span>
            </div>
            @if($smsMessage->template)
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Template</span>
                <span class="text-gray-800">{{ $smsMessage->template->name }}</span>
            </div>
            @endif
            @if($smsMessage->scheduled_at)
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Scheduled For</span>
                <span class="text-gray-800">{{ $smsMessage->scheduled_at->format('M d, Y H:i') }}</span>
            </div>
            @endif
            @if($smsMessage->sent_at)
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Sent At</span>
                <span class="text-gray-800">{{ $smsMessage->sent_at->format('M d, Y H:i') }}</span>
            </div>
            @endif
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('sms.index') }}" class="text-sm text-gray-600 hover:text-gray-800"><i class="fas fa-arrow-left mr-1"></i> Back to Messages</a>
    </div>
</div>
@endsection
