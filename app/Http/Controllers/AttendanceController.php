<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Member;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with(['member', 'service']);

        if ($request->filled('date')) {
            $query->where('service_date', $request->date);
        }

        if ($request->filled('service_type')) {
            $query->where('service_type', $request->service_type);
        }

        $attendances = $query->latest('service_date')->paginate(20);

        $attendanceSummary = Attendance::select('service_date', DB::raw('COUNT(*) as total'))
            ->groupBy('service_date')
            ->orderByDesc('service_date')
            ->take(10)
            ->get();

        return view('attendance.index', compact('attendances', 'attendanceSummary'));
    }

    public function create()
    {
        $members = Member::where('membership_status', 'active')->orderBy('first_name')->get();
        $services = Service::orderByDesc('service_date')->take(20)->get();
        return view('attendance.create', compact('members', 'services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_date' => 'required|date',
            'service_type' => 'nullable|string|max:255',
            'service_id' => 'nullable|exists:services,id',
            'members' => 'required|array|min:1',
            'members.*' => 'exists:members,id',
            'check_in_method' => 'nullable|in:manual,qr',
        ]);

        $count = 0;
        foreach ($validated['members'] as $memberId) {
            Attendance::updateOrCreate(
                [
                    'member_id' => $memberId,
                    'service_date' => $validated['service_date'],
                    'service_id' => $validated['service_id'] ?? null,
                ],
                [
                    'service_type' => $validated['service_type'] ?? null,
                    'check_in_time' => now()->format('H:i:s'),
                    'check_in_method' => $validated['check_in_method'] ?? 'manual',
                ]
            );
            $count++;
        }

        return redirect()->route('attendance.index')
            ->with('success', "{$count} attendance records saved successfully.");
    }

    public function show(Request $request, $date)
    {
        $attendances = Attendance::with('member')
            ->where('service_date', $date)
            ->get();

        $absentMembers = Member::where('membership_status', 'active')
            ->whereNotIn('id', $attendances->pluck('member_id'))
            ->get();

        return view('attendance.show', compact('attendances', 'absentMembers', 'date'));
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return back()->with('success', 'Attendance record removed.');
    }

    public function qrCheckin(Request $request)
    {
        $request->validate(['qr_code' => 'required|string']);

        $member = Member::where('qr_code', $request->qr_code)->first();

        if (!$member) {
            return response()->json(['success' => false, 'message' => 'Member not found.'], 404);
        }

        $attendance = Attendance::updateOrCreate(
            [
                'member_id' => $member->id,
                'service_date' => today(),
            ],
            [
                'check_in_time' => now()->format('H:i:s'),
                'check_in_method' => 'qr',
            ]
        );

        return response()->json([
            'success' => true,
            'message' => "Welcome, {$member->full_name}!",
            'member' => $member,
        ]);
    }
}
