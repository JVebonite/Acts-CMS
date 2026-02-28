<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Visitor;
use App\Models\Attendance;
use App\Models\Donation;
use App\Models\Expense;
use App\Models\Equipment;
use App\Models\Cluster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalMembers = Member::count();
        $activeMembers = Member::where('membership_status', 'active')->count();
        $totalVisitors = Visitor::count();
        $newVisitorsThisMonth = Visitor::whereMonth('visit_date', now()->month)
            ->whereYear('visit_date', now()->year)->count();

        $totalDonationsThisMonth = Donation::whereMonth('donation_date', now()->month)
            ->whereYear('donation_date', now()->year)->sum('amount');
        $totalExpensesThisMonth = Expense::whereMonth('expense_date', now()->month)
            ->whereYear('expense_date', now()->year)->sum('amount');

        $todayAttendance = Attendance::where('service_date', today())->count();
        $totalEquipment = Equipment::count();
        $totalClusters = Cluster::where('status', 'active')->count();

        $recentMembers = Member::latest()->take(5)->get();
        $recentVisitors = Visitor::latest()->take(5)->get();
        $recentDonations = Donation::with('member')->latest()->take(5)->get();

        $monthlyDonations = Donation::select(
            DB::raw('MONTH(donation_date) as month'),
            DB::raw('SUM(amount) as total')
        )
            ->whereYear('donation_date', now()->year)
            ->groupBy(DB::raw('MONTH(donation_date)'))
            ->orderBy('month')
            ->get();

        $monthlyAttendance = Attendance::select(
            DB::raw('MONTH(service_date) as month'),
            DB::raw('COUNT(DISTINCT member_id) as total')
        )
            ->whereYear('service_date', now()->year)
            ->groupBy(DB::raw('MONTH(service_date)'))
            ->orderBy('month')
            ->get();

        return view('dashboard', compact(
            'totalMembers', 'activeMembers', 'totalVisitors', 'newVisitorsThisMonth',
            'totalDonationsThisMonth', 'totalExpensesThisMonth', 'todayAttendance',
            'totalEquipment', 'totalClusters', 'recentMembers', 'recentVisitors',
            'recentDonations', 'monthlyDonations', 'monthlyAttendance'
        ));
    }
}
