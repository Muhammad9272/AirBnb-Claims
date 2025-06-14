<div class="card">
    <div class="card-header border-0 align-items-center d-flex">
        <h4 class="card-title mb-0 flex-grow-1">Orders & Sales Analytics</h4>
        <div>
            <button data-value="30d" class="btn btn-soft-primary btn-sm autoFilters">30 Days</button>
            <button data-value="6m" class="btn btn-soft-secondary btn-sm autoFilters">6M</button>
            <button data-value="1y" class="btn btn-soft-secondary btn-sm autoFilters">1Y</button>
            <button data-value="all" class="btn btn-soft-secondary btn-sm autoFilters">All</button>
        </div>
    </div><!-- end card header -->

    <div class="card-header p-0 border-0 bg-soft-light">
        <div class="row g-0 text-center">
            <div class="col-6 col-sm-6">
                <div class="p-3 border border-dashed border-start-0">
                    <h5 class="mb-1"><span class="counter-value"
                            data-target="{{ $stats['orders_count'] ?? 0 }}">0</span></h5>
                    <p class="text-muted mb-0">Orders</p>
                </div>
            </div>
            <!--end col-->
            <div class="col-6 col-sm-6">
                <div class="p-3 border border-dashed border-start-0">
                    <h5 class="mb-1">{{ Helpers::getCurrencySymbol() }}<span class="counter-value"
                            data-target="{{ $stats['total_sales'] ?? 0 }}">0</span></h5>
                    <p class="text-muted mb-0">Sales</p>
                </div>
            </div>
            <!--end col-->
        </div>
    </div><!-- end card header -->

    <div class="card-body p-0 pb-2">
        <div class="w-100">
            <div id="sales-chart"
                data-colors='["--vz-primary", "--vz-success", "--vz-danger"]'
                class="apex-charts" dir="ltr"></div>
        </div>
    </div><!-- end card body -->
</div><!-- end card -->

<div class="card">
    <div class="card-header align-items-center d-flex">
        <h4 class="card-title mb-0 flex-grow-1">Sales Overview</h4>
        <div class="flex-shrink-0">
            <div class="dropdown card-header-dropdown">
                <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="text-muted">Last 30 Days<i class="mdi mdi-chevron-down ms-1"></i></span>
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="#">Last 30 Days</a>
                    <a class="dropdown-item" href="#">Last Month</a>
                    <a class="dropdown-item" href="#">Last Year</a>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div id="sales-chart" data-colors='["--vz-primary", "--vz-success", "--vz-danger"]' class="apex-charts" dir="ltr"></div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Prepare the sales data for ApexCharts
    function prepareSalesData() {
        const dates = [];
        const amounts = [];
        
        @if(isset($salesData) && count($salesData) > 0)
            @foreach($salesData as $data)
                dates.push("{{ \Carbon\Carbon::parse($data->date)->format('M d') }}");
                amounts.push({{ $data->total }});
            @endforeach
        @else
            // Default empty data
            for(let i = 0; i < 7; i++) {
                const d = new Date();
                d.setDate(d.getDate() - i);
                dates.unshift(d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));
                amounts.unshift(0);
            }
        @endif
        
        return { dates, amounts };
    }
    
    // Initialize sales chart if element exists
    const salesChartElement = document.getElementById('sales-chart');
    if (salesChartElement) {
        const { dates, amounts } = prepareSalesData();
        
        const options = {
            series: [{
                name: 'Sales',
                data: amounts
            }],
            chart: {
                height: 350,
                type: 'area',
                toolbar: {
                    show: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    inverseColors: false,
                    opacityFrom: 0.45,
                    opacityTo: 0.05,
                    stops: [20, 100, 100, 100]
                }
            },
            xaxis: {
                categories: dates
            },
            tooltip: {
                y: {
                    formatter: function(value) {
                        return "{{ Helpers::getCurrencySymbol() }}" + value.toFixed(2);
                    }
                }
            }
        };
        
        const chart = new ApexCharts(salesChartElement, options);
        chart.render();
    }
});
</script>