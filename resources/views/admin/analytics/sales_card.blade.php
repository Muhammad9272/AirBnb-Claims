<div class="col-xl-4 col-md-6">
    <!-- card -->
    <div class="card card-animate">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <p class="text-uppercase fw-medium text-muted mb-0">{{ $title ?? 'Total Sales' }}</p>
                    <h4 class="fs-22 fw-semibold mb-0">{{ isset($stats['total_sales']) ? Helpers::setCurrency($stats['total_sales']) : '$0.00' }}</h4>
                    <p class="text-muted mt-2 mb-0">
                        <span class="badge bg-light text-success mb-0">
                            <i class="ri-shopping-cart-line align-middle"></i>
                            {{ $stats['orders_count'] ?? 0 }} Orders
                        </span>
                    </p>
                </div>
                <div class="avatar-sm flex-shrink-0">
                    <span class="avatar-title bg-soft-{{ $color ?? 'success' }} rounded fs-3">
                        <i class="{{ $icon ?? 'bx bx-dollar-circle' }} text-{{ $color ?? 'success' }}"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div><!-- end col -->

<div class="col-xl-4 col-md-6">
    <!-- card -->
    <div class="card card-animate">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1 overflow-hidden">
                    <p
                        class="text-uppercase fw-medium text-muted text-truncate mb-0">
                        Orders</p>
                </div>
                <div class="flex-shrink-0">
                    <h5 class="text-{{$ordersPercentage>0?'success':'danger'}} fs-14 mb-0">
                        <i class="ri-arrow-right-{{$ordersPercentage>0?'up':'down'}}-line fs-13 align-middle"></i>
                        {{$ordersPercentage}} %
                    </h5>
                </div>
            </div>
            <div class="d-flex align-items-end justify-content-between mt-4">
                <div>
                    <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span
                            class="counter-value" data-target="{{$currentOrders}}">0</span></h4>
                    <a href="{{route('admin.orders.index')}}" class="text-decoration-underline">View all orders</a>
                </div>
                <div class="avatar-sm flex-shrink-0">
                    <span class="avatar-title bg-soft-info rounded fs-3">
                        <i class="bx bx-shopping-bag text-info"></i>
                    </span>
                </div>
            </div>
        </div><!-- end card body -->
    </div><!-- end card -->
</div><!-- end col -->

<div class="col-xl-4 col-md-6">
    <!-- card -->
    <div class="card card-animate">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1 overflow-hidden">
                    <p
                        class="text-uppercase fw-medium text-muted text-truncate mb-0">
                        Customers</p>
                </div>
                <div class="flex-shrink-0">
                    <h5 class="text-{{$usersPercentage>0?'success':'danger'}} fs-14 mb-0">
                        <i class="ri-arrow-right-{{$usersPercentage>0?'up':'down'}}-line fs-13 align-middle"></i>
                        {{$usersPercentage}} %
                    </h5>
                </div>
            </div>
            <div class="d-flex align-items-end justify-content-between mt-4">
                <div>
                    <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span
                            class="counter-value" data-target="{{$totalusers}}">0</span>
                    </h4>
                    <a href="{{route('admin.users.index')}}" class="text-decoration-underline">See details</a>
                </div>
                <div class="avatar-sm flex-shrink-0">
                    <span class="avatar-title bg-soft-warning rounded fs-3">
                        <i class="bx bx-user-circle text-warning"></i>
                    </span>
                </div>
            </div>
        </div><!-- end card body -->
    </div><!-- end card -->
</div><!-- end col -->

