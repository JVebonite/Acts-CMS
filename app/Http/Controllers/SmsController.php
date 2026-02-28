<?php

namespace App\Http\Controllers;

use App\Models\SmsMessage;
use App\Models\SmsTemplate;
use App\Models\Member;
use App\Models\Cluster;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    public function index(Request $request)
    {
        $query = SmsMessage::with('sender', 'template');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $messages = $query->latest()->paginate(15);
        return view('sms.index', compact('messages'));
    }

    public function create()
    {
        $templates = SmsTemplate::orderBy('name')->get();
        $members = Member::where('membership_status', 'active')
            ->whereNotNull('phone')
            ->orderBy('first_name')
            ->get();
        $clusters = Cluster::where('status', 'active')->orderBy('name')->get();

        return view('sms.create', compact('templates', 'members', 'clusters'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'recipient_type' => 'required|in:individual,group,all',
            'recipients' => 'required_if:recipient_type,individual',
            'cluster_id' => 'required_if:recipient_type,group|nullable|exists:clusters,id',
            'message' => 'required|string|max:160',
            'template_id' => 'nullable|exists:sms_templates,id',
            'scheduled_at' => 'nullable|date|after:now',
        ]);

        $recipientList = '';
        $totalRecipients = 0;

        if ($validated['recipient_type'] === 'all') {
            $members = Member::where('membership_status', 'active')
                ->whereNotNull('phone')->get();
            $recipientList = $members->pluck('phone')->implode(',');
            $totalRecipients = $members->count();
        } elseif ($validated['recipient_type'] === 'group') {
            $cluster = Cluster::with('members')->find($validated['cluster_id']);
            $members = $cluster->members->filter(fn($m) => !empty($m->phone));
            $recipientList = $members->pluck('phone')->implode(',');
            $totalRecipients = $members->count();
        } else {
            $recipientList = $validated['recipients'];
            $totalRecipients = count(explode(',', $recipientList));
        }

        $sms = SmsMessage::create([
            'sender_id' => auth()->id(),
            'recipient_type' => $validated['recipient_type'],
            'recipients' => $recipientList,
            'message' => $validated['message'],
            'template_id' => $validated['template_id'] ?? null,
            'scheduled_at' => $validated['scheduled_at'] ?? null,
            'status' => $validated['scheduled_at'] ? 'scheduled' : 'pending',
            'total_recipients' => $totalRecipients,
        ]);

        return redirect()->route('sms.index')
            ->with('success', "SMS queued for {$totalRecipients} recipients.");
    }

    public function show(SmsMessage $smsMessage)
    {
        return view('sms.show', compact('smsMessage'));
    }

    // --- Templates ---
    public function templates()
    {
        $templates = SmsTemplate::latest()->paginate(15);
        return view('sms.templates', compact('templates'));
    }

    public function storeTemplate(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'nullable|string|max:255',
        ]);

        SmsTemplate::create($validated);

        return redirect()->route('sms.templates')
            ->with('success', 'Template created successfully.');
    }

    public function destroyTemplate(SmsTemplate $template)
    {
        $template->delete();
        return redirect()->route('sms.templates')
            ->with('success', 'Template deleted successfully.');
    }
}
