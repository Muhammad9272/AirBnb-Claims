@extends('admin.layouts.master')
@section('title') Survey Analytics @endsection

@section('css')
<link href="{{ URL::asset('assets/libs/apexcharts/apexcharts.min.css') }}" rel="stylesheet">
@endsection

@section('content')

<div class="row">
    <!-- Total Responses Card -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-0">Total Responses</p>
                        <h4 class="fs-22 fw-semibold mb-0">{{ $totalResponses }}</h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-primary rounded fs-3">
                            <i class="ri-questionnaire-line text-primary"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Response Rate Card -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-0">Response Rate</p>
                        <h4 class="fs-22 fw-semibold mb-0">{{ number_format($responseRate, 1) }}%</h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-success rounded fs-3">
                            <i class="ri-percentage-line text-success"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Pie Chart -->
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Response Distribution</h4>
            </div>
            <div class="card-body">
                <div id="pieChart" class="apex-charts" dir="ltr"></div>
            </div>
        </div>
    </div>

    <!-- Bar Chart -->
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Responses Over Time</h4>
            </div>
            <div class="card-body">
                <div id="barChart" class="apex-charts" dir="ltr"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Detailed Table -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Detailed Responses</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Option</th>
                                <th>Responses</th>
                                <th>Percentage</th>
                                <th>Last Response</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($surveyData as $data)
                            <tr>
                                <td>{{ $data->option_text }}</td>
                                <td>{{ $data->responses_count }}</td>
                                <td>{{ number_format(($data->responses_count / $totalResponses) * 100, 1) }}%</td>
                                <td>{{ $data->last_response ? $data->last_response->format('M d, Y H:i') : 'N/A' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ URL::asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>

<script>
    // Pie Chart
    var pieOptions = {
        series: {!! json_encode($surveyData->pluck('responses_count')) !!},
        chart: {
            type: 'pie',
            height: 320,
        },
        labels: {!! json_encode($surveyData->pluck('option_text')) !!},
        legend: {
            position: 'bottom'
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    height: 280
                }
            }
        }]
    };

    var pieChart = new ApexCharts(document.querySelector("#pieChart"), pieOptions);
    pieChart.render();

    // Bar Chart
    var barOptions = {
        series: [{
            name: 'Responses',
            data: {!! json_encode($timeSeriesData) !!}
        }],
        chart: {
            type: 'bar',
            height: 320,
            toolbar: {
                show: false
            }
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '45%',
            },
        },
        dataLabels: {
            enabled: false
        },
        xaxis: {
            type: 'datetime',
            labels: {
                rotate: -45
            }
        },
        yaxis: {
            title: {
                text: 'Number of Responses'
            }
        },
        fill: {
            opacity: 0.85
        }
    };

    var barChart = new ApexCharts(document.querySelector("#barChart"), barOptions);
    barChart.render();
</script>
@endsection