<div class="card">
    <div class="card-header align-items-center d-flex">
        <h4 class="card-title mb-0 flex-grow-1">Sales by Coupon</h4>
    </div><!-- end card header -->
    <div class="card-body">
            <div id=""
                data-series="[{{$salesByCoupon['couponOrders']}}, {{$salesByCoupon['withoutCouponOrders']}}]"
                data-labels='["Coupon", "Without Coupon"]'
                data-total-label="Total Orders"
                class="apex-charts apex-charts-dashboard"
                dir="ltr">
            </div>
            <div class="mt-3">
                <div class="d-flex justify-content-between border-bottom border-bottom-dashed py-2">
                    <p class="fw-medium mb-0"><i class="ri-checkbox-blank-circle-fill text-success align-middle me-2"></i> Coupon</p>
                    <div>
                        <span class="text-muted pe-5">{{$salesByCoupon['couponOrders']}}</span>
                        <span class="text-success fw-medium fs-12">{{Helpers::setCurrency($salesByCoupon['couponOrdersPercentage'])}}%</span>
                    </div>
                </div><!-- end -->
                <div class="d-flex justify-content-between border-bottom border-bottom-dashed py-2">
                    <p class="fw-medium mb-0"><i class="ri-checkbox-blank-circle-fill text-info align-middle me-2"></i> Without Coupon</p>
                    <div>
                        <span class="text-muted pe-5">{{$salesByCoupon['withoutCouponOrders']}} </span>
                        <span class="text-success fw-medium fs-12">{{Helpers::setCurrency($salesByCoupon['withoutCouponOrdersPercentage'])}}%</span>
                    </div>
                </div><!-- end -->
            </div>
    </div><!-- end card body -->
</div><!-- end card -->

<div class="card">
    <div class="card-header align-items-center d-flex">
        <h4 class="card-title mb-0 flex-grow-1">Top Coupons</h4>
    </div>
    <div class="card-body">
        @if(isset($coupons) && count($coupons) > 0)
            <div class="table-responsive">
                <table class="table table-bordered align-middle table-nowrap mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Code</th>
                            <th>Uses</th>
                            <th>Total Discount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($coupons as $coupon)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="bg-light rounded p-1 me-2">
                                            <i class="bx bxs-discount text-primary"></i>
                                        </span>
                                        <span class="fw-medium">{{ $coupon->coupon_code }}</span>
                                    </div>
                                </td>
                                <td>{{ $coupon->uses }}</td>
                                <td>{{ Helpers::setCurrency($coupon->total_discount) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-4">
                <div class="avatar-md mx-auto mb-4">
                    <div class="avatar-title bg-light text-secondary rounded-circle fs-24">
                        <i class="ri-coupon-line"></i>
                    </div>
                </div>
                <h5 class="mb-2">No Coupon Usage Yet</h5>
                <p class="text-muted">There's no coupon usage data available at the moment.</p>
            </div>
        @endif
    </div>
</div>