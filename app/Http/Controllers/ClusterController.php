<?php

namespace App\Http\Controllers;

use App\Models\Cluster;
use App\Models\ClusterFollowup;
use App\Models\Member;
use Illuminate\Http\Request;

class ClusterController extends Controller
{
    public function index()
    {
        $clusters = Cluster::with('leader')
            ->withCount('members')
            ->latest()
            ->paginate(15);

        return view('clusters.index', compact('clusters'));
    }

    public function create()
    {
        $members = Member::where('membership_status', 'active')->orderBy('first_name')->get();
        return view('clusters.create', compact('members'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'leader_id' => 'nullable|exists:members,id',
            'description' => 'nullable|string',
            'meeting_day' => 'nullable|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'meeting_time' => 'nullable|date_format:H:i',
            'location' => 'nullable|string|max:255',
            'status' => 'nullable|in:active,inactive',
        ]);

        $cluster = Cluster::create($validated);

        return redirect()->route('clusters.show', $cluster)
            ->with('success', 'Cluster created successfully.');
    }

    public function show(Cluster $cluster)
    {
        $cluster->load(['leader', 'members', 'followups.member', 'followups.followUpPerson']);
        $availableMembers = Member::where('membership_status', 'active')
            ->whereNotIn('id', $cluster->members->pluck('id'))
            ->orderBy('first_name')
            ->get();

        return view('clusters.show', compact('cluster', 'availableMembers'));
    }

    public function edit(Cluster $cluster)
    {
        $members = Member::where('membership_status', 'active')->orderBy('first_name')->get();
        return view('clusters.edit', compact('cluster', 'members'));
    }

    public function update(Request $request, Cluster $cluster)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'leader_id' => 'nullable|exists:members,id',
            'description' => 'nullable|string',
            'meeting_day' => 'nullable|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'meeting_time' => 'nullable|date_format:H:i',
            'location' => 'nullable|string|max:255',
            'status' => 'nullable|in:active,inactive',
        ]);

        $cluster->update($validated);

        return redirect()->route('clusters.show', $cluster)
            ->with('success', 'Cluster updated successfully.');
    }

    public function destroy(Cluster $cluster)
    {
        $cluster->delete();
        return redirect()->route('clusters.index')
            ->with('success', 'Cluster deleted successfully.');
    }

    public function addMember(Request $request, Cluster $cluster)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'role' => 'nullable|string|max:255',
        ]);

        $cluster->members()->syncWithoutDetaching([
            $request->member_id => [
                'role' => $request->role ?? 'member',
                'joined_date' => now(),
            ],
        ]);

        return back()->with('success', 'Member added to cluster.');
    }

    public function removeMember(Cluster $cluster, Member $member)
    {
        $cluster->members()->detach($member->id);
        return back()->with('success', 'Member removed from cluster.');
    }

    // --- Follow-ups ---
    public function followups(Request $request)
    {
        $query = ClusterFollowup::with(['cluster', 'member', 'followUpPerson']);

        if ($request->filled('cluster_id')) {
            $query->where('cluster_id', $request->cluster_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $followups = $query->latest('follow_up_date')->paginate(15);
        $clusters = Cluster::where('status', 'active')->orderBy('name')->get();

        return view('clusters.followups', compact('followups', 'clusters'));
    }

    public function storeFollowup(Request $request)
    {
        $validated = $request->validate([
            'cluster_id' => 'required|exists:clusters,id',
            'member_id' => 'required|exists:members,id',
            'follow_up_by' => 'nullable|exists:members,id',
            'follow_up_date' => 'required|date',
            'type' => 'required|in:phone_call,visit,message,email,other',
            'notes' => 'nullable|string',
            'status' => 'required|in:pending,completed,no_response,rescheduled',
        ]);

        ClusterFollowup::create($validated);

        return back()->with('success', 'Follow-up recorded successfully.');
    }

    public function updateFollowup(Request $request, ClusterFollowup $followup)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,completed,no_response,rescheduled',
            'notes' => 'nullable|string',
        ]);

        $followup->update($validated);

        return back()->with('success', 'Follow-up updated.');
    }
}
