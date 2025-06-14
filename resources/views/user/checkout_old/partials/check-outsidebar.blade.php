<div class="sticky-side-div">
    <div class="card yyy">
        <div class="card-header border-bottom-dashed">
            <h5 class="card-title mb-0">Order Summary</h5>
        </div>
        <div class="card-header bg-soft-light border-bottom-dashed">
            <div class="text-center">
                <h6 class="mb-2">Have a <span class="fw-semibold">promo</span> code ?</h6>
            </div>
            <form method="POST" action="{{ route('user.cart.addCoupon') }}">
                @csrf
                <div class="hstack gap-3 px-3 mx-n3">
                    <input class="form-control me-auto" type="text" placeholder="Enter coupon code"
                        aria-label="Add Promo Code here..." name="coupon_code" required>
                    <button type="submit" class="btn btn-success w-xs">Apply</button>
                </div>
            </form>
        </div>
        <div class="card-body pt-2">
            <div class="table-responsive">
                <table class="table table-borderless mb-0">
                    <tbody>
                        <tr>
                            <td>Sub Total :</td>
                            <td class="text-end" id="cart-subtotal">
                                {{ Helpers::setCurrency($cartTotal['subtotal'] ?? null) }}</td>
                        </tr>
                        @if ($cartTotal['discount'] > 0)
                            <tr>
                                <td>Discount <span class="text-muted">({{ Session::get('coupon_code') }})</span> : </td>
                                <td class="text-end" id="cart-discount">-
                                    {{ Helpers::setCurrency($cartTotal['discount'] ?? null) }}</td>
                            </tr>
                        @endif
                        @if ($cartTotal['tax'] > 0)
                            <tr>
                                <td>Estimated Tax (12.5%) : </td>
                                <td class="text-end" id="cart-tax">
                                    {{ Helpers::setCurrency($cartTotal['tax'] ?? null) }}</td>
                            </tr>
                        @endif
                        <tr class="table-active">
                            <th>Total ({{$gs->currency_code}}) :</th>
                            <td class="text-end">
                                <span class="fw-semibold" id="cart-total">
                                    {{ Helpers::setCurrency($cartTotal['total'] ?? null) }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- end table-responsive -->
        </div>
    </div>
    <img class="w-100" src="{{ URL::asset('assets/images/ad-placeholder.png') }}" alt=""
        class="img-fluid d-block rounded">

</div>
<!-- end stickey -->
