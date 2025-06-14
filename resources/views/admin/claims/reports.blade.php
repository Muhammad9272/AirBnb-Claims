@extends('admin.layouts.master')
@section('title') Claims Reports @endsection
@section('css')
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/apexcharts/dist/apexcharts.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1') <a href="{{ route('admin.dashboard') }}">Dashboard</a> @endslot
@slot('li_2') <a href="{{ route('admin.claims.index') }}">Claims</a> @endslot
@slot('title') Reports & Analytics @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h5 class="card-title mb-0 flex-grow-1">Claims Reports</h5>
                <div class="flex-shrink-0">
                    <form method="GET" class="d-flex gap-2">
                        <select name="period" class="form-select form-select-sm">
                            <option value="week" {{ $period == 'week' ? 'selected' : '' }}>Last Week</option>
                            <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Last Month</option>
                            <option value="quarter" {{ $period == 'quarter' ? 'selected' : '' }}>Last Quarter</option>
                            <option value="year" {{ $period == 'year' ? 'selected' : '' }}>Last Year</option>
                        </select>
                        <select name="status" class="form-select form-select-sm">
                            <option value="all" {{ $status == 'all' ? 'selected' : '' }}>All Statuses</option>
                            <option value="submitted" {{ $status == 'submitted' ? 'selected' : '' }}>Submitted</option>
                            <option value="in_review" {{ $status == 'in_review' ? 'selected' : '' }}>In Review</option>
                            <option value="pending_evidence" {{ $status == 'pending_evidence' ? 'selected' : '' }}>Pending Evidence</option>
                            <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ $status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="paid" {{ $status == 'paid' ? 'selected' : '' }}>Paid</option>
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                        <a href="{{ route('admin.claims.export', ['period' => $period, 'status' => $status]) }}" class="btn btn-sm btn-info">
                            <i class="ri-download-2-line align-bottom me-1"></i> Export
                        </a>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="text-muted mb-3">
                    Showing data from <strong>{{ $startDate->format('M d, Y') }}</strong> to <strong>{{ $endDate->format('M d, Y') }}</strong>
                </div>
                
                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium mb-2">Total Claims</p>
                                        <h4 class="mb-0">{{ $reportData['total_claims'] }}</h4>
                                    </div>
                                    <div class="mini-stat-icon avatar-sm align-self-center rounded-circle bg-primary">
                                        <span class="avatar-title">
                                            <i class="ri-file-list-3-line font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium mb-2">Total Amount Claimed</p>
                                        <h4 class="mb-0">${{ number_format($reportData['total_amount'], 2) }}</h4>
                                    </div>
                                    <div class="mini-stat-icon avatar-sm align-self-center rounded-circle bg-secondary">
                                        <span class="avatar-title">
                                            <i class="ri-money-dollar-circle-line font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium mb-2">Total Approved Amount</p>
                                        <h4 class="mb-0">${{ number_format($reportData['approved_amount'], 2) }}</h4>
                                    </div>
                                    <div class="mini-stat-icon avatar-sm align-self-center rounded-circle bg-success">
                                        <span class="avatar-title">
                                            <i class="ri-check-double-line font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium mb-2">Total Commission</p>
                                        <h4 class="mb-0">${{ number_format($reportData['commission_earned'], 2) }}</h4>
                                    </div>
                                    <div class="mini-stat-icon avatar-sm align-self-center rounded-circle bg-info">
                                        <span class="avatar-title">
                                            <i class="ri-percent-line font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Status Breakdown</h5>
                            </div>
                            <div class="card-body">
                                <div id="status-chart" class="apex-charts" dir="ltr" style="height: 300px;"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Status Distribution</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-centered table-nowrap mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Status</th>
                                                <th>Count</th>
                                                <th>Percentage</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $totalClaims = $reportData['total_claims'] ?: 1; // Prevent division by zero
                                            @endphp
                                            <tr>
                                                <td>Submitted</td>
                                                <td>{{ $reportData['status_breakdown']['submitted'] }}</td>
                                                <td>{{ round(($reportData['status_breakdown']['submitted'] / $totalClaims) * 100) }}%</td>
                                            </tr>
                                            <tr>
                                                <td>In Review</td>
                                                <td>{{ $reportData['status_breakdown']['in_review'] }}</td>
                                                <td>{{ round(($reportData['status_breakdown']['in_review'] / $totalClaims) * 100) }}%</td>
                                            </tr>
                                            <tr>
                                                <td>Pending Evidence</td>
                                                <td>{{ $reportData['status_breakdown']['pending_evidence'] }}</td>
                                                <td>{{ round(($reportData['status_breakdown']['pending_evidence'] / $totalClaims) * 100) }}%</td>
                                            </tr>
                                            <tr>
                                                <td>Approved</td>
                                                <td>{{ $reportData['status_breakdown']['approved'] }}</td>
                                                <td>{{ round(($reportData['status_breakdown']['approved'] / $totalClaims) * 100) }}%</td>
                                            </tr>
                                            <tr>
                                                <td>Rejected</td>
                                                <td>{{ $reportData['status_breakdown']['rejected'] }}</td>
                                                <td>{{ round(($reportData['status_breakdown']['rejected'] / $totalClaims) * 100) }}%</td>
                                            </tr>
                                            <tr>
                                                <td>Paid</td>
                                                <td>{{ $reportData['status_breakdown']['paid'] }}</td>
                                                <td>{{ round(($reportData['status_breakdown']['paid'] / $totalClaims) * 100) }}%</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    $(document).ready(function() {
        // Status breakdown chart
        var statusOptions = {
            series: [
                {{ $reportData['status_breakdown']['submitted'] }}, 
                {{ $reportData['status_breakdown']['in_review'] }}, 
                {{ $reportData['status_breakdown']['pending_evidence'] }}, 
                {{ $reportData['status_breakdown']['approved'] }}, 
                {{ $reportData['status_breakdown']['rejected'] }}, 
                {{ $reportData['status_breakdown']['paid'] }}
            ],
            chart: {
                type: 'donut',
                height: 300
            },
            labels: ['Submitted', 'In Review', 'Pending Evidence', 'Approved', 'Rejected', 'Paid'],
            colors: ['#3b76e1', '#5fd0f3', '#f9c846', '#63ed7a', '#f44336', '#6c757d'],
            legend: {
                position: 'bottom'
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        var statusChart = new ApexCharts(document.querySelector("#status-chart"), statusOptions);
        statusChart.render();
    });
</script>
@endsection
