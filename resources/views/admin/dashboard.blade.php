@extends('admin.layouts.master')

@section('title', 'Dashboard')

@section('css')
    <!-- Reuse any necessary CSS (e.g., card styles, chart library CSS, etc.) -->
    <link href="{{ asset('assets/admin/libs/apexcharts/apexcharts.min.css') }}" rel="stylesheet">
    <style>
        .dashboard-card {
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        .card-animate {
            transition: transform 0.2s ease-in-out;
        }
        .card-animate:hover {
            transform: translateY(-3px);
        }
    </style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Dashboard</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Cards Row -->
    <div class="row g-4 mb-5">
        <!-- Total Users Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate overflow-hidden dashboard-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="avatar-sm">
                            <div class="avatar-title bg-success bg-gradient text-white rounded-3 shadow-sm">
                                <i class="ri-user-3-line fs-4"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <p class="fw-medium text-uppercase text-muted mb-1 fs-14">Total Users</p>
                            <h4 class="fs-22 fw-semibold mb-0">{{ number_format($totalUsers) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Claims Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate overflow-hidden dashboard-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="avatar-sm">
                            <div class="avatar-title bg-danger bg-gradient text-white rounded-3 shadow-sm">
                                <i class="ri-clipboard-line fs-4"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <p class="fw-medium text-uppercase text-muted mb-1 fs-14">Total Claims</p>
                            <h4 class="fs-22 fw-semibold mb-0">{{ number_format($totalClaims) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Subscriptions Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate overflow-hidden dashboard-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="avatar-sm">
                            <div class="avatar-title bg-info bg-gradient text-white rounded-3 shadow-sm">
                                <i class="ri-wallet-fill fs-4"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <p class="fw-medium text-uppercase text-muted mb-1 fs-14">Active Subscriptions</p>
                            <h4 class="fs-22 fw-semibold mb-0">{{ number_format($totalSubscriptions) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Approval Rate Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate overflow-hidden dashboard-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="avatar-sm">
                            <div class="avatar-title bg-warning bg-gradient text-white rounded-3 shadow-sm">
                                <i class="ri-percent-line fs-4"></i>
                            </div>
                        </div>
                        <div class="ms-3 w-100">
                            <p class="fw-medium text-uppercase text-muted mb-1 fs-14">Approval Rate</p>
                            <h4 class="fs-22 fw-semibold mb-1">{{ $approvalRate }}%</h4>
                            <div class="progress animated-progress custom-progress progress-sm">
                                <div class="progress-bar bg-warning" role="progressbar"
                                     style="width: {{ $approvalRate }}%" aria-valuenow="{{ $approvalRate }}"
                                     aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                            <p class="text-muted fs-12 mb-0 mt-1">{{ $approvalRate }}% of goal</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Claims Status Statistics Row -->
    <div class="row g-4 mb-5">
        <!-- Pending Claims Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate overflow-hidden dashboard-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="avatar-sm">
                            <div class="avatar-title bg-secondary bg-gradient text-white rounded-3 shadow-sm">
                                <i class="ri-time-line fs-4"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <p class="fw-medium text-uppercase text-muted mb-1 fs-14">Pending Claims</p>
                            <h4 class="fs-22 fw-semibold mb-0">{{ $pendingClaims }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- In Progress Claims Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate overflow-hidden dashboard-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="avatar-sm">
                            <div class="avatar-title bg-primary bg-gradient text-white rounded-3 shadow-sm">
                                <i class="ri-loader-2-line fs-4"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <p class="fw-medium text-uppercase text-muted mb-1 fs-14">In Progress</p>
                            <h4 class="fs-22 fw-semibold mb-0">{{ $inProgressClaims }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Approved Claims Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate overflow-hidden dashboard-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="avatar-sm">
                            <div class="avatar-title bg-success bg-gradient text-white rounded-3 shadow-sm">
                                <i class="ri-checkbox-circle-line fs-4"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <p class="fw-medium text-uppercase text-muted mb-1 fs-14">Approved Claims</p>
                            <h4 class="fs-22 fw-semibold mb-0">{{ $approvedClaims }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rejected Claims Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate overflow-hidden dashboard-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="avatar-sm">
                            <div class="avatar-title bg-danger bg-gradient text-white rounded-3 shadow-sm">
                                <i class="ri-close-circle-line fs-4"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <p class="fw-medium text-uppercase text-muted mb-1 fs-14">Rejected Claims</p>
                            <h4 class="fs-22 fw-semibold mb-0">{{ $rejectedClaims }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <!-- Monthly Revenue (Line Chart) -->
        <div class="col-xl-8">
            <div class="card dashboard-card h-100 mb-4">
                <div class="card-header d-flex align-items-center">
                    <h5 class="card-title mb-0">Monthly Subscriptions Revenue</h5>
                </div>
                <div class="card-body">
                    <div class="chart-area" style="position: relative; height: 380px;">
                        <canvas id="monthlyRevenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Claims by Status (Doughnut Chart) -->
        <div class="col-xl-4">
            <div class="card dashboard-card h-100 mb-4">
                <div class="card-header d-flex align-items-center">
                    <h5 class="card-title mb-0">Claims by Status</h5>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2" style="position: relative; height: 270px;">
                        <canvas id="claimsByStatusChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="mr-2">
                            <i class="fas fa-circle text-secondary"></i> Pending
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-primary"></i> In Progress
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-success"></i> Approved
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-danger"></i> Rejected
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Charts Row -->
    <div class="row">
        <!-- Weekly Claims (Bar Chart) -->
        <div class="col-xl-8">
            <div class="card dashboard-card mb-4 h-100">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Weekly Claims</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar" style="position: relative; height: 300px;">
                        <canvas id="weeklyClaimsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subscription Plan Distribution (Doughnut Chart) -->
        <div class="col-xl-4">
            <div class="card dashboard-card mb-4 h-100">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Subscription Plans</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2" style="position: relative; height: 250px;">
                        <canvas id="planDistributionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Claims & New Users Tables Row -->
    <div class="row mt-5 pt-2 mb-5">
        <!-- Recent Claims Table -->
        <div class="col-lg-6">
            <div class="card dashboard-card mb-4 h-100">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Claims</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless table-nowrap align-middle mb-0">
                            <thead class="table-light text-muted">
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentClaims as $key=>$claim)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.claims.show', $claim->id) }}" class="fw-medium link-primary">
                                                #{{ $key+1}}
                                            </a>
                                        </td>
                                        <td>{{ $claim->user->name }}</td>
                                        <td>${{ number_format($claim->amount_requested, 2) }}</td>
                                        <td>
                                            <span class="badge 
                                                {{ $claim->status == 'pending' ? 'bg-secondary' : '' }}
                                                {{ $claim->status == 'in_progress' ? 'bg-primary' : '' }}

                                                 {{ $claim->status == 'under_review' ? 'bg-primary' : '' }}
                                                {{ $claim->status == 'approved' ? 'bg-success' : '' }}
                                                {{ $claim->status == 'rejected' ? 'bg-danger' : '' }}">
                                                {{ ucfirst(str_replace('_', ' ', $claim->status)) }}
                                            </span>
                                        </td>
                                        <td>{{ $claim->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-3">No recent claims</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.claims.index') }}" class="btn btn-sm btn-primary">View All Claims</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- New Users Table -->
        <div class="col-lg-6">
            <div class="card dashboard-card mb-4 h-100">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">New Users</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless table-nowrap align-middle mb-0">
                            <thead class="table-light text-muted">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Joined</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($newUsers as $key=>$user)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>
                                            <a href="{{ route('admin.users.show', $user->id) }}" class="fw-medium link-primary">
                                                {{ $user->name }}
                                            </a>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-3">No new users</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-primary">View All Users</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Monthly Revenue Chart
        let ctxMR = document.getElementById("monthlyRevenueChart");
        new Chart(ctxMR, {
            type: 'line',
            data: {
                labels: {!! json_encode($monthlyRevenue['labels']) !!},
                datasets: [{
                    label: "Revenue",
                    lineTension: 0.3,
                    backgroundColor: "rgba(78, 115, 223, 0.05)",
                    borderColor: "rgba(78, 115, 223, 1)",
                    pointRadius: 3,
                    pointBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointBorderColor: "rgba(78, 115, 223, 1)",
                    data: {!! json_encode($monthlyRevenue['data']) !!}
                }]
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: { left: 10, right: 25, top: 25, bottom: 0 }
                },
                scales: {
                    xAxes: [{
                        gridLines: { display: false, drawBorder: false },
                        ticks: { maxTicksLimit: 6 }
                    }],
                    yAxes: [{
                        ticks: {
                            maxTicksLimit: 5,
                            padding: 10,
                            callback: function(value) { return '$' + value; }
                        },
                        gridLines: {
                            color: "rgb(234, 236, 244)",
                            zeroLineColor: "rgb(234, 236, 244)",
                            drawBorder: false,
                            borderDash: [2],
                            zeroLineBorderDash: [2]
                        }
                    }]
                },
                legend: { display: false },
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                    callbacks: {
                        label: function(tooltipItem, chart) {
                            let datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                            return datasetLabel + ': $' + tooltipItem.yLabel;
                        }
                    }
                }
            }
        });

        // Claims by Status Chart
        let ctxCS = document.getElementById("claimsByStatusChart");
        new Chart(ctxCS, {
            type: 'doughnut',
            data: {
                labels: ["Pending", "In Progress", "Approved", "Rejected"],
                datasets: [{
                    data: [
                        {{ $claimsByStatus['pending'] }},
                        {{ $claimsByStatus['in_progress'] }},
                        {{ $claimsByStatus['approved'] }},
                        {{ $claimsByStatus['rejected'] }}
                    ],
                    backgroundColor: ['#858796', '#4e73df', '#1cc88a', '#e74a3b'],
                    hoverBackgroundColor: ['#717380', '#2e59d9', '#17a673', '#d52a1a'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)"
                }]
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10
                },
                legend: { display: false },
                cutoutPercentage: 70
            }
        });

        // Weekly Claims Chart
        let ctxWC = document.getElementById("weeklyClaimsChart");
        new Chart(ctxWC, {
            type: 'bar',
            data: {
                labels: {!! json_encode($weeklyClaims['labels']) !!},
                datasets: [{
                    label: "Claims",
                    backgroundColor: "#4e73df",
                    hoverBackgroundColor: "#2e59d9",
                    borderColor: "#4e73df",
                    data: {!! json_encode($weeklyClaims['data']) !!}
                }]
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: { left: 10, right: 25, top: 25, bottom: 0 }
                },
                scales: {
                    xAxes: [{
                        gridLines: { display: false, drawBorder: false },
                        ticks: { maxTicksLimit: 7 }
                    }],
                    yAxes: [{
                        ticks: {
                            min: 0,
                            maxTicksLimit: 5,
                            padding: 10
                        },
                        gridLines: {
                            color: "rgb(234, 236, 244)",
                            zeroLineColor: "rgb(234, 236, 244)",
                            drawBorder: false,
                            borderDash: [2],
                            zeroLineBorderDash: [2]
                        }
                    }]
                },
                legend: { display: false },
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10
                }
            }
        });

        // Subscription Plan Distribution Chart
        let ctxPD = document.getElementById("planDistributionChart");
        new Chart(ctxPD, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($planDistribution['labels']) !!},
                datasets: [{
                    data: {!! json_encode($planDistribution['data']) !!},
                    backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'],
                    hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf', '#dda20a', '#be2617'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)"
                }]
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10
                },
                legend: {
                    display: true,
                    position: 'bottom'
                },
                cutoutPercentage: 70
            }
        });
    });
    </script>
@endsection
