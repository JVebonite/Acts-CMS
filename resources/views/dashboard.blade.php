@extends('layouts.app')

@section('title', 'Dashboard - ACTS Church CMS')
@section('page-title', 'Dashboard')
@section('page-description', 'Overview of church activities and statistics')

@section('content')
{{-- Stats Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-4 mb-6">
    <div class="stat-card bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Members</p>
                <p class="text-2xl font-bold text-navy-800 mt-1">{{ number_format($totalMembers) }}</p>
                <p class="text-xs text-green-600 mt-1"><i class="fas fa-user-check mr-1"></i>{{ $activeMembers }} active</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                <i class="fas fa-users text-navy-600 text-lg"></i>
            </div>
        </div>
    </div>

    <div class="stat-card bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Visitors</p>
                <p class="text-2xl font-bold text-navy-800 mt-1">{{ number_format($totalVisitors) }}</p>
                <p class="text-xs text-gold-600 mt-1"><i class="fas fa-calendar mr-1"></i>{{ $newVisitorsThisMonth }} this month</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center">
                <i class="fas fa-user-plus text-gold-600 text-lg"></i>
            </div>
        </div>
    </div>

    <div class="stat-card bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Today's Attendance</p>
                <p class="text-2xl font-bold text-navy-800 mt-1">{{ number_format($todayAttendance) }}</p>
                <p class="text-xs text-gray-500 mt-1"><i class="fas fa-clock mr-1"></i>{{ now()->format('M d') }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center">
                <i class="fas fa-clipboard-check text-green-600 text-lg"></i>
            </div>
        </div>
    </div>

    <div class="stat-card bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Monthly Income</p>
                <p class="text-2xl font-bold text-navy-800 mt-1">{{ number_format($totalDonationsThisMonth, 2) }}</p>
                <p class="text-xs text-red-500 mt-1"><i class="fas fa-arrow-down mr-1"></i>{{ number_format($totalExpensesThisMonth, 2) }} spent</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center">
                <i class="fas fa-wallet text-emerald-600 text-lg"></i>
            </div>
        </div>
    </div>

    <div class="stat-card bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Clusters</p>
                <p class="text-2xl font-bold text-navy-800 mt-1">{{ number_format($totalClusters) }}</p>
                <p class="text-xs text-gray-500 mt-1"><i class="fas fa-tools mr-1"></i>{{ $totalEquipment }} equipment</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center">
                <i class="fas fa-people-arrows text-purple-600 text-lg"></i>
            </div>
        </div>
    </div>
</div>

{{-- Charts Row --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h3 class="text-sm font-semibold text-navy-800 mb-4"><i class="fas fa-chart-line mr-2 text-gold-500"></i>Monthly Donations ({{ now()->year }})</h3>
        <canvas id="donationsChart" height="200"></canvas>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h3 class="text-sm font-semibold text-navy-800 mb-4"><i class="fas fa-chart-bar mr-2 text-gold-500"></i>Monthly Attendance ({{ now()->year }})</h3>
        <canvas id="attendanceChart" height="200"></canvas>
    </div>
</div>

{{-- Recent Activity --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Recent Members --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-navy-800"><i class="fas fa-user-friends mr-2 text-gold-500"></i>Recent Members</h3>
            <a href="{{ route('members.index') }}" class="text-xs text-gold-600 hover:text-gold-700">View All</a>
        </div>
        <div class="space-y-3">
            @forelse($recentMembers as $member)
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded-full gradient-navy flex items-center justify-center flex-shrink-0">
                    <span class="text-white text-xs font-bold">{{ substr($member->first_name, 0, 1) }}</span>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ $member->full_name }}</p>
                    <p class="text-xs text-gray-500">{{ $member->created_at->diffForHumans() }}</p>
                </div>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                    {{ $member->membership_status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                    {{ ucfirst($member->membership_status) }}
                </span>
            </div>
            @empty
            <p class="text-sm text-gray-500 text-center py-4">No members yet.</p>
            @endforelse
        </div>
    </div>

    {{-- Recent Visitors --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-navy-800"><i class="fas fa-user-plus mr-2 text-gold-500"></i>Recent Visitors</h3>
            <a href="{{ route('visitors.index') }}" class="text-xs text-gold-600 hover:text-gold-700">View All</a>
        </div>
        <div class="space-y-3">
            @forelse($recentVisitors as $visitor)
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
                    <span class="text-gold-700 text-xs font-bold">{{ substr($visitor->first_name, 0, 1) }}</span>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ $visitor->full_name }}</p>
                    <p class="text-xs text-gray-500">{{ $visitor->visit_date->format('M d, Y') }}</p>
                </div>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                    {{ $visitor->follow_up_status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                    {{ ucfirst($visitor->follow_up_status) }}
                </span>
            </div>
            @empty
            <p class="text-sm text-gray-500 text-center py-4">No visitors yet.</p>
            @endforelse
        </div>
    </div>

    {{-- Recent Donations --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-navy-800"><i class="fas fa-hand-holding-usd mr-2 text-gold-500"></i>Recent Donations</h3>
            <a href="{{ route('finance.donations') }}" class="text-xs text-gold-600 hover:text-gold-700">View All</a>
        </div>
        <div class="space-y-3">
            @forelse($recentDonations as $donation)
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-dollar-sign text-green-600 text-xs"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ $donation->member ? $donation->member->full_name : 'Anonymous' }}</p>
                    <p class="text-xs text-gray-500">{{ ucfirst($donation->donation_type) }} &middot; {{ $donation->donation_date->format('M d') }}</p>
                </div>
                <span class="text-sm font-semibold text-green-700">{{ number_format($donation->amount, 2) }}</span>
            </div>
            @empty
            <p class="text-sm text-gray-500 text-center py-4">No donations yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

    // Donations Chart
    const donationsData = @json($monthlyDonations);
    const donationLabels = donationsData.map(d => months[d.month - 1]);
    const donationValues = donationsData.map(d => parseFloat(d.total));

    new Chart(document.getElementById('donationsChart'), {
        type: 'line',
        data: {
            labels: donationLabels,
            datasets: [{
                label: 'Donations',
                data: donationValues,
                borderColor: '#d4a017',
                backgroundColor: 'rgba(212, 160, 23, 0.1)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#d4a017',
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });

    // Attendance Chart
    const attendanceData = @json($monthlyAttendance);
    const attLabels = attendanceData.map(d => months[d.month - 1]);
    const attValues = attendanceData.map(d => parseInt(d.total));

    new Chart(document.getElementById('attendanceChart'), {
        type: 'bar',
        data: {
            labels: attLabels,
            datasets: [{
                label: 'Unique Attendees',
                data: attValues,
                backgroundColor: 'rgba(30, 58, 123, 0.8)',
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
</script>
@endpush
