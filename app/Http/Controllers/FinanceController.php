<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Pledge;
use App\Models\Campaign;
use App\Models\Member;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    public function index()
    {
        $totalDonations = Donation::sum('amount');
        $totalExpenses = Expense::sum('amount');
        $monthlyDonations = Donation::whereMonth('donation_date', now()->month)
            ->whereYear('donation_date', now()->year)->sum('amount');
        $monthlyExpenses = Expense::whereMonth('expense_date', now()->month)
            ->whereYear('expense_date', now()->year)->sum('amount');

        $recentDonations = Donation::with('member')->latest('donation_date')->take(10)->get();
        $recentExpenses = Expense::with('category')->latest('expense_date')->take(10)->get();
        $activeCampaigns = Campaign::where('status', 'active')->get();

        return view('finance.index', compact(
            'totalDonations', 'totalExpenses', 'monthlyDonations', 'monthlyExpenses',
            'recentDonations', 'recentExpenses', 'activeCampaigns'
        ));
    }

    // --- Donations ---
    public function donations(Request $request)
    {
        $query = Donation::with('member', 'campaign');

        if ($request->filled('search')) {
            $query->whereHas('member', function ($q) use ($request) {
                $q->where('first_name', 'like', "%{$request->search}%")
                  ->orWhere('last_name', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('donation_type', $request->type);
        }

        if ($request->filled('date_from')) {
            $query->where('donation_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('donation_date', '<=', $request->date_to);
        }

        $donations = $query->latest('donation_date')->paginate(15);
        $members = Member::orderBy('first_name')->get();
        $campaigns = Campaign::orderBy('name')->get();

        return view('finance.donations', compact('donations', 'members', 'campaigns'));
    }

    public function storeDonation(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'nullable|exists:members,id',
            'amount' => 'required|numeric|min:0.01',
            'donation_date' => 'required|date',
            'donation_type' => 'required|in:tithe,offering,special,mission,building,other',
            'payment_method' => 'required|in:cash,cheque,bank_transfer,mobile_money,card,online',
            'campaign_id' => 'nullable|exists:campaigns,id',
            'receipt_number' => 'nullable|string|unique:donations,receipt_number',
            'notes' => 'nullable|string',
            'is_recurring' => 'nullable|boolean',
            'recurring_frequency' => 'nullable|in:weekly,monthly,quarterly,annually',
        ]);

        $validated['recorded_by'] = auth()->id();
        $donation = Donation::create($validated);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'created_donation',
            'model_type' => 'Donation',
            'model_id' => $donation->id,
            'new_values' => $validated,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('finance.donations')
            ->with('success', 'Donation recorded successfully.');
    }

    public function destroyDonation(Donation $donation)
    {
        $donation->delete();
        return redirect()->route('finance.donations')
            ->with('success', 'Donation deleted successfully.');
    }

    // --- Expenses ---
    public function expenses(Request $request)
    {
        $query = Expense::with('category', 'approver');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('approval_status', $request->status);
        }

        $expenses = $query->latest('expense_date')->paginate(15);
        $categories = ExpenseCategory::orderBy('name')->get();

        return view('finance.expenses', compact('expenses', 'categories'));
    }

    public function storeExpense(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'nullable|exists:expense_categories,id',
            'amount' => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'description' => 'nullable|string',
            'receipt_path' => 'nullable|file|max:5120',
            'department' => 'nullable|string|max:255',
            'vendor' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        if ($request->hasFile('receipt_path')) {
            $validated['receipt_path'] = $request->file('receipt_path')
                ->store('expenses/receipts', 'public');
        }

        $validated['recorded_by'] = auth()->id();
        $validated['approval_status'] = 'pending';

        $expense = Expense::create($validated);

        return redirect()->route('finance.expenses')
            ->with('success', 'Expense recorded successfully.');
    }

    public function approveExpense(Expense $expense)
    {
        $expense->update([
            'approval_status' => 'approved',
            'approved_by' => auth()->id(),
        ]);

        return back()->with('success', 'Expense approved.');
    }

    public function rejectExpense(Expense $expense)
    {
        $expense->update([
            'approval_status' => 'rejected',
            'approved_by' => auth()->id(),
        ]);

        return back()->with('success', 'Expense rejected.');
    }

    // --- Pledges ---
    public function pledges(Request $request)
    {
        $query = Pledge::with('member', 'campaign');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pledges = $query->latest()->paginate(15);
        $members = Member::orderBy('first_name')->get();
        $campaigns = Campaign::orderBy('name')->get();

        return view('finance.pledges', compact('pledges', 'members', 'campaigns'));
    }

    public function storePledge(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'campaign_id' => 'nullable|exists:campaigns,id',
            'amount_pledged' => 'required|numeric|min:0.01',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'frequency' => 'required|in:one_time,weekly,monthly,quarterly,annually',
            'notes' => 'nullable|string',
        ]);

        Pledge::create($validated);

        return redirect()->route('finance.pledges')
            ->with('success', 'Pledge recorded successfully.');
    }

    // --- Campaigns ---
    public function campaigns()
    {
        $campaigns = Campaign::withCount('donations')->withSum('donations', 'amount')->latest()->paginate(15);
        return view('finance.campaigns', compact('campaigns'));
    }

    public function storeCampaign(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_amount' => 'required|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'nullable|in:active,completed,cancelled',
        ]);

        Campaign::create($validated);

        return redirect()->route('finance.campaigns')
            ->with('success', 'Campaign created successfully.');
    }

    // --- Expense Categories ---
    public function expenseCategories()
    {
        $categories = ExpenseCategory::withSum('expenses', 'amount')->get();
        return view('finance.expense-categories', compact('categories'));
    }

    public function storeExpenseCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'budget_amount' => 'nullable|numeric|min:0',
        ]);

        ExpenseCategory::create($validated);

        return redirect()->route('finance.expense-categories')
            ->with('success', 'Expense category created successfully.');
    }
}
