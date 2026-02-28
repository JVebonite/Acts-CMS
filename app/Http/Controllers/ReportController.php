<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Visitor;
use App\Models\Attendance;
use App\Models\Donation;
use App\Models\Expense;
use App\Models\Pledge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function memberReport(Request $request)
    {
        $query = Member::query();

        if ($request->filled('status')) {
            $query->where('membership_status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->where('membership_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('membership_date', '<=', $request->date_to);
        }

        $members = $query->get();

        $summary = [
            'total' => $members->count(),
            'active' => $members->where('membership_status', 'active')->count(),
            'inactive' => $members->where('membership_status', 'inactive')->count(),
            'male' => $members->where('gender', 'male')->count(),
            'female' => $members->where('gender', 'female')->count(),
        ];

        return view('reports.members', compact('members', 'summary'));
    }

    public function attendanceReport(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->toDateString());
        $dateTo = $request->get('date_to', now()->toDateString());

        $attendances = Attendance::with('member')
            ->whereBetween('service_date', [$dateFrom, $dateTo])
            ->get();

        $dailySummary = $attendances->groupBy('service_date')
            ->map(fn($group) => $group->count());

        $memberAttendance = $attendances->groupBy('member_id')
            ->map(fn($group) => [
                'member' => $group->first()->member,
                'count' => $group->count(),
            ])->sortByDesc('count');

        return view('reports.attendance', compact('attendances', 'dailySummary', 'memberAttendance', 'dateFrom', 'dateTo'));
    }

    public function financeReport(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfYear()->toDateString());
        $dateTo = $request->get('date_to', now()->toDateString());

        $donations = Donation::with('member')
            ->whereBetween('donation_date', [$dateFrom, $dateTo]);

        $expenses = Expense::with('category')
            ->whereBetween('expense_date', [$dateFrom, $dateTo]);

        $donationsByType = (clone $donations)->select('donation_type', DB::raw('SUM(amount) as total'))
            ->groupBy('donation_type')->get();

        $expensesByCategory = (clone $expenses)->join('expense_categories', 'expenses.category_id', '=', 'expense_categories.id')
            ->select('expense_categories.name', DB::raw('SUM(expenses.amount) as total'))
            ->groupBy('expense_categories.name')->get();

        $totalDonations = (clone $donations)->sum('amount');
        $totalExpenses = (clone $expenses)->sum('amount');

        $monthlyTrend = (clone $donations)->select(
            DB::raw('MONTH(donation_date) as month'),
            DB::raw('SUM(amount) as total')
        )->groupBy(DB::raw('MONTH(donation_date)'))->orderBy('month')->get();

        return view('reports.finance', compact(
            'donationsByType', 'expensesByCategory', 'totalDonations',
            'totalExpenses', 'monthlyTrend', 'dateFrom', 'dateTo'
        ));
    }
}
