<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use App\Models\Member;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class VisitorController extends Controller
{
    public function index(Request $request)
    {
        $query = Visitor::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('follow_up_status')) {
            $query->where('follow_up_status', $request->follow_up_status);
        }

        $visitors = $query->latest()->paginate(15);
        return view('visitors.index', compact('visitors'));
    }

    public function create()
    {
        return view('visitors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'visit_date' => 'required|date',
            'invited_by' => 'nullable|string|max:255',
            'service_attended' => 'nullable|string|max:255',
            'follow_up_status' => 'nullable|in:pending,contacted,follow_up,completed',
            'follow_up_notes' => 'nullable|string',
            'notes' => 'nullable|string',
            'prayer_request' => 'nullable|string',
        ]);

        $visitor = Visitor::create($validated);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'created_visitor',
            'model_type' => 'Visitor',
            'model_id' => $visitor->id,
            'new_values' => $validated,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('visitors.show', $visitor)
            ->with('success', 'Visitor recorded successfully.');
    }

    public function show(Visitor $visitor)
    {
        return view('visitors.show', compact('visitor'));
    }

    public function edit(Visitor $visitor)
    {
        return view('visitors.edit', compact('visitor'));
    }

    public function update(Request $request, Visitor $visitor)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'visit_date' => 'required|date',
            'invited_by' => 'nullable|string|max:255',
            'service_attended' => 'nullable|string|max:255',
            'follow_up_status' => 'nullable|in:pending,contacted,follow_up,completed',
            'follow_up_notes' => 'nullable|string',
            'notes' => 'nullable|string',
            'prayer_request' => 'nullable|string',
        ]);

        $visitor->update($validated);

        return redirect()->route('visitors.show', $visitor)
            ->with('success', 'Visitor updated successfully.');
    }

    public function destroy(Visitor $visitor)
    {
        $visitor->delete();
        return redirect()->route('visitors.index')
            ->with('success', 'Visitor deleted successfully.');
    }

    public function convertToMember(Visitor $visitor)
    {
        $member = Member::create([
            'first_name' => $visitor->first_name,
            'last_name' => $visitor->last_name,
            'email' => $visitor->email,
            'phone' => $visitor->phone,
            'address' => $visitor->address,
            'membership_status' => 'active',
            'membership_date' => now(),
            'created_by' => auth()->id(),
        ]);

        $visitor->update([
            'converted_to_member' => true,
            'member_id' => $member->id,
        ]);

        return redirect()->route('members.show', $member)
            ->with('success', 'Visitor converted to member successfully.');
    }
}
