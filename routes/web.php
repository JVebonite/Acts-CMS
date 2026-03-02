<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\\Http\\Controllers\\ClusterController;

/*
|--------------------------------------------------------------------------
| Health Check Route
|--------------------------------------------------------------------------
*/
Route::get('/health', function () {
    return response('OK', 200);
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Members
    Route::resource('members', MemberController::class);

    // Visitors
    Route::resource('visitors', VisitorController::class);
    Route::post('/visitors/{visitor}/convert', [VisitorController::class, 'convertToMember'])->name('visitors.convert');

    // Attendance
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/create', [AttendanceController::class, 'create'])->name('attendance.create');
    Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/attendance/{date}', [AttendanceController::class, 'show'])->name('attendance.show');
    Route::delete('/attendance/{attendance}', [AttendanceController::class, 'destroy'])->name('attendance.destroy');
    Route::post('/attendance/qr-checkin', [AttendanceController::class, 'qrCheckin'])->name('attendance.qr-checkin');

    // Finance
    Route::get('/finance', [FinanceController::class, 'index'])->name('finance.index');
    Route::get('/finance/donations', [FinanceController::class, 'donations'])->name('finance.donations');
    Route::post('/finance/donations', [FinanceController::class, 'storeDonation'])->name('finance.donations.store');
    Route::delete('/finance/donations/{donation}', [FinanceController::class, 'destroyDonation'])->name('finance.donations.destroy');
    Route::get('/finance/expenses', [FinanceController::class, 'expenses'])->name('finance.expenses');
    Route::post('/finance/expenses', [FinanceController::class, 'storeExpense'])->name('finance.expenses.store');
    Route::post('/finance/expenses/{expense}/approve', [FinanceController::class, 'approveExpense'])->name('finance.expenses.approve');
    Route::post('/finance/expenses/{expense}/reject', [FinanceController::class, 'rejectExpense'])->name('finance.expenses.reject');
    Route::get('/finance/pledges', [FinanceController::class, 'pledges'])->name('finance.pledges');
    Route::post('/finance/pledges', [FinanceController::class, 'storePledge'])->name('finance.pledges.store');
    Route::get('/finance/campaigns', [FinanceController::class, 'campaigns'])->name('finance.campaigns');
    Route::post('/finance/campaigns', [FinanceController::class, 'storeCampaign'])->name('finance.campaigns.store');
    Route::get('/finance/expense-categories', [FinanceController::class, 'expenseCategories'])->name('finance.expense-categories');
    Route::post('/finance/expense-categories', [FinanceController::class, 'storeExpenseCategory'])->name('finance.expense-categories.store');

    // Bulk SMS
    Route::get('/sms', [SmsController::class, 'index'])->name('sms.index');
    Route::get('/sms/create', [SmsController::class, 'create'])->name('sms.create');
    Route::post('/sms', [SmsController::class, 'store'])->name('sms.store');
    Route::get('/sms/{smsMessage}', [SmsController::class, 'show'])->name('sms.show');
    Route::get('/sms-templates', [SmsController::class, 'templates'])->name('sms.templates');
    Route::post('/sms-templates', [SmsController::class, 'storeTemplate'])->name('sms.templates.store');
    Route::delete('/sms-templates/{template}', [SmsController::class, 'destroyTemplate'])->name('sms.templates.destroy');

    // Equipment
    Route::resource('equipment', EquipmentController::class);

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/members', [ReportController::class, 'memberReport'])->name('reports.members');
    Route::get('/reports/attendance', [ReportController::class, 'attendanceReport'])->name('reports.attendance');
    Route::get('/reports/finance', [ReportController::class, 'financeReport'])->name('reports.finance');

    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    Route::get('/settings/profile', [SettingController::class, 'profile'])->name('settings.profile');
    Route::put('/settings/profile', [SettingController::class, 'updateProfile'])->name('settings.profile.update');
    Route::put('/settings/password', [SettingController::class, 'changePassword'])->name('settings.password.update');
    Route::get('/settings/users', [SettingController::class, 'users'])->name('settings.users');
    Route::post('/settings/users', [SettingController::class, 'storeUser'])->name('settings.users.store');
    Route::post('/settings/users/{user}/toggle', [SettingController::class, 'toggleUserStatus'])->name('settings.users.toggle');

    // Cluster Follow-up
    Route::resource('clusters', ClusterController::class);
    Route::post('/clusters/{cluster}/members', [ClusterController::class, 'addMember'])->name('clusters.members.add');
    Route::delete('/clusters/{cluster}/members/{member}', [ClusterController::class, 'removeMember'])->name('clusters.members.remove');
    Route::get('/cluster-followups', [ClusterController::class, 'followups'])->name('clusters.followups');
    Route::post('/cluster-followups', [ClusterController::class, 'storeFollowup'])->name('clusters.followups.store');
    Route::put('/cluster-followups/{followup}', [ClusterController::class, 'updateFollowup'])->name('clusters.followups.update');
});
