<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Family;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = Member::with('family');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('membership_status', $request->status);
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        $members = $query->latest()->paginate(15);
        return view('members.index', compact('members'));
    }

    public function create()
    {
        $families = Family::orderBy('family_name')->get();
        return view('members.create', compact('families'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:members,email',
            'phone' => 'nullable|string|max:20',
            'alternate_phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'marital_status' => 'nullable|in:single,married,widowed,divorced',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_relationship' => 'nullable|string|max:255',
            'membership_status' => 'nullable|in:active,inactive,transferred,deceased',
            'membership_date' => 'nullable|date',
            'baptism_date' => 'nullable|date',
            'wedding_anniversary' => 'nullable|date',
            'occupation' => 'nullable|string|max:255',
            'employer' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'family_id' => 'nullable|exists:families,id',
            'family_role' => 'nullable|string|max:255',
            'membership_class' => 'nullable|string|max:255',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('profile_photo')) {
            $validated['profile_photo'] = $request->file('profile_photo')
                ->store('members/photos', 'public');
        }

        $validated['qr_code'] = Str::uuid()->toString();
        $validated['created_by'] = auth()->id();

        $member = Member::create($validated);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'created_member',
            'model_type' => 'Member',
            'model_id' => $member->id,
            'new_values' => $validated,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('members.show', $member)
            ->with('success', 'Member created successfully.');
    }

    public function show(Member $member)
    {
        $member->load(['family', 'attendances', 'donations', 'pledges', 'documents', 'clusters', 'prayerRequests']);
        return view('members.show', compact('member'));
    }

    public function edit(Member $member)
    {
        $families = Family::orderBy('family_name')->get();
        return view('members.edit', compact('member', 'families'));
    }

    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:members,email,' . $member->id,
            'phone' => 'nullable|string|max:20',
            'alternate_phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'marital_status' => 'nullable|in:single,married,widowed,divorced',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_relationship' => 'nullable|string|max:255',
            'membership_status' => 'nullable|in:active,inactive,transferred,deceased',
            'membership_date' => 'nullable|date',
            'baptism_date' => 'nullable|date',
            'wedding_anniversary' => 'nullable|date',
            'occupation' => 'nullable|string|max:255',
            'employer' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'family_id' => 'nullable|exists:families,id',
            'family_role' => 'nullable|string|max:255',
            'membership_class' => 'nullable|string|max:255',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        $oldValues = $member->toArray();

        if ($request->hasFile('profile_photo')) {
            $validated['profile_photo'] = $request->file('profile_photo')
                ->store('members/photos', 'public');
        }

        $member->update($validated);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated_member',
            'model_type' => 'Member',
            'model_id' => $member->id,
            'old_values' => $oldValues,
            'new_values' => $validated,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('members.show', $member)
            ->with('success', 'Member updated successfully.');
    }

    public function destroy(Member $member)
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'deleted_member',
            'model_type' => 'Member',
            'model_id' => $member->id,
            'old_values' => $member->toArray(),
            'ip_address' => request()->ip(),
        ]);

        $member->delete();

        return redirect()->route('members.index')
            ->with('success', 'Member deleted successfully.');
    }
}
