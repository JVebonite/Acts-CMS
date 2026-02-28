@extends('layouts.app')

@section('title', 'Compose SMS - ACTS Church CMS')
@section('page-title', 'Compose SMS')
@section('page-description', 'Send bulk SMS messages')

@section('content')
<div class="max-w-3xl">
    <form method="POST" action="{{ route('sms.store') }}" x-data="{ recipientType: 'all', charCount: 0 }">
        @csrf
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <h3 class="text-sm font-semibold text-navy-800 mb-4 flex items-center">
                <i class="fas fa-users mr-2 text-gold-500"></i> Recipients
            </h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Recipient Type <span class="text-red-500">*</span></label>
                    <select name="recipient_type" x-model="recipientType" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                        <option value="all">All Active Members</option>
                        <option value="group">Cluster/Group</option>
                        <option value="individual">Individual Numbers</option>
                    </select>
                </div>

                <div x-show="recipientType === 'group'">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Select Cluster</label>
                    <select name="cluster_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                        <option value="">Select a cluster</option>
                        @foreach($clusters as $cluster)
                        <option value="{{ $cluster->id }}">{{ $cluster->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div x-show="recipientType === 'individual'">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone Numbers</label>
                    <textarea name="recipients" rows="3" placeholder="Enter comma-separated phone numbers..."
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500"></textarea>
                    <p class="text-xs text-gray-500 mt-1">Separate numbers with commas</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <h3 class="text-sm font-semibold text-navy-800 mb-4 flex items-center">
                <i class="fas fa-comment mr-2 text-gold-500"></i> Message
            </h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Template (Optional)</label>
                    <select name="template_id" id="templateSelect"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                        <option value="">No template</option>
                        @foreach($templates as $tpl)
                        <option value="{{ $tpl->id }}" data-content="{{ $tpl->content }}">{{ $tpl->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Message <span class="text-red-500">*</span></label>
                    <textarea name="message" id="messageField" rows="4" required maxlength="160"
                              x-on:input="charCount = $event.target.value.length"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500"></textarea>
                    <p class="text-xs text-right mt-1" :class="charCount > 140 ? 'text-red-500' : 'text-gray-500'">
                        <span x-text="charCount"></span>/160 characters
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Schedule (Optional)</label>
                    <input type="datetime-local" name="scheduled_at"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gold-500">
                    <p class="text-xs text-gray-500 mt-1">Leave empty to send immediately</p>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end space-x-3">
            <a href="{{ route('sms.index') }}" class="px-5 py-2.5 text-sm text-gray-600 border border-gray-300 rounded-lg">Cancel</a>
            <button type="submit" class="px-5 py-2.5 gradient-gold text-white text-sm font-medium rounded-lg hover:opacity-90 shadow-sm">
                <i class="fas fa-paper-plane mr-2"></i> Send SMS
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.getElementById('templateSelect').addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        if (option.dataset.content) {
            document.getElementById('messageField').value = option.dataset.content;
            document.getElementById('messageField').dispatchEvent(new Event('input'));
        }
    });
</script>
@endpush
@endsection
