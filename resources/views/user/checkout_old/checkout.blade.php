@extends('user.layouts.master')
@section('title')
    @lang('translation.checkout')
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18"> Checkout</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{route('user.dashboard')}}">Home</a></li> 
                                       
                    <li class="breadcrumb-item active">Checkout</li>
                    
                </ol>
            </div>

        </div>
    </div>
</div> 



    @if (Session::has('cart'))
        <div class="row">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-body checkout-tab">

                        <div class="step-arrow-nav mt-n3 mx-n3 mb-3">

                            <ul class="ts-tab nav nav-pills  nav-justified " role="tablist">
                                <li class="nav-item waves-effect waves-light">
                                    <a href="{{ route('user.cart') }}"
                                        class="nav-link fs-15 p-3 {{ Route::currentRouteName() == 'user.cart' ? 'active' : '' }}"
                                        id="pills-shopping-cart-tab">
                                        <span>
                                            Shopping Cart
                                        </span>
                                    </a>
                                </li>

                                <li class="nav-item waves-effect waves-light">
                                    <a href="{{ route('user.checkout') }}"
                                        class="nav-link fs-15 p-3 h-100 {{ Route::currentRouteName() == 'user.checkout' ? 'active' : '' }} "
                                        id="pills-payment-tab">
                                        <span>
                                            Payment
                                        </span>
                                    </a>
                                </li>

                            </ul>

                            {{-- <ul class="ts-tab nav nav-pills  nav-justified " role="tablist">

                                <li class="nav-item text-nowrap" role="presentation">
                                    <a href="{{ route('user.cart') }}"
                                        class="nav-link fs-15 p-3 {{ Route::currentRouteName() == 'user.cart' ? 'active' : '' }}"
                                        id="pills-shopping-cart-tab"><i
                                            class="bx bx-shopping-bag fs-16 p-2 bg-soft-primary text-primary rounded-circle align-middle me-2"></i>
                                        Shopping Cart</a>
                                </li>
                                <li class="nav-item text-nowrap" role="presentation">
                                    <a href="{{ route('user.checkout') }}"
                                        class="nav-link fs-15 p-3 h-100 {{ Route::currentRouteName() == 'user.checkout' ? 'active' : '' }} "
                                        id="pills-payment-tab"><i
                                            class="ri-bank-card-line fs-16 p-2 bg-soft-primary text-primary rounded-circle align-middle me-2"></i>
                                        Payment Info</a>
                                </li>
                                <li class="nav-item text-nowrap" role="presentation">
                                    <a href="" class="nav-link fs-15 p-3 h-100" id="pills-finish-tab"><i
                                            class="ri-checkbox-circle-line fs-16 p-2 bg-soft-primary text-primary rounded-circle align-middle me-2"></i>Finish</a>
                                </li>
                            </ul> --}}
                        </div>

                        <div class="tab-content">

                            @if (Route::currentRouteName() == 'user.cart')
                                <div class="tab-pane fade show active" id="pills-shopping-cart" role="tabpanel"
                                    aria-labelledby="pills-shopping-cart-tab">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="mb-1">Cart Information</h5>
                                            <p class="text-muted mb-4">Your Cart
                                                ({{ Session::has('cart') ? count(Session::get('cart')) : 0 }} Items)</p>
                                        </div>
                                        <button type="button" class="btn btn-danger" onclick="removeAll()">Empty
                                            Cart</button>

                                    </div>

                                    <div class="mt-4">
                                        @include('user.checkout.partials.cart')
                                    </div>
                                   {{--  <div class=" d-flex justify-content-end gap-3">
                                        <h5 class="fs-16 mb-0">Total:</h5>
                                        <h5 class="fs-16 text-primary mb-0">$500.00</h5>
                                    </div> --}}

                                    <hr>
                                    <div class="d-flex align-items-start gap-3 mt-4">
                                        <a href="{{ route('user.checkout') }}"
                                            class="btn btn-warning text-white btn-label right ms-auto "><i
                                                class="ri-bank-card-line label-icon align-middle fs-16 ms-2"></i>Continue
                                            to Payment</a>
                                    </div>
                                </div>
                            @endif
                            <!-- end tab pane -->
                            @if (Route::currentRouteName() == 'user.checkout')
                                <div class="tab-pane fade  show active" id="pills-payment" role="tabpanel"
                                    aria-labelledby="pills-payment-tab">
                                    

                                    <div class="row g-4">
                                        
                                        <div class="col-lg-4 col-sm-6">
                                                <div class="form-check card-radio">
                                                    <input id="paymentMethodAliPay" name="paymentMethod"
                                                        type="radio" class="form-check-input" value="alipay">
                                                    <label class="form-check-label" for="paymentMethodAliPay">
                                                        <span class="fs-16 text-muted me-2"><i
                                                                class="ri-alipay-fill align-bottom"></i></span>
                                                        <span class="fs-13 text-wrap">AliPay</span>
                                                    </label>
                                                </div>
                                        </div>
                                        <div class="col-lg-4 col-sm-6">
                                                <div class="form-check card-radio">
                                                    <input id="paymentMethodStripe" name="paymentMethod"
                                                        type="radio" class="form-check-input" checked value="stripe"> 
                                                    <label class="form-check-label" for="paymentMethodStripe">
                                                        <span class="fs-16 text-muted me-2"><i
                                                                class="ri-bank-card-fill align-bottom"></i></span>
                                                        <span class="fs-13 text-wrap">Credit / Debit
                                                            Card</span>
                                                    </label>
                                                </div>
                                        </div>
                                        <div class="col-lg-4 col-sm-6">
                                                <div class="form-check card-radio">
                                                    <input id="paymentMethodPayPal" name="paymentMethod"
                                                        type="radio" class="form-check-input" value="paypal">
                                                    <label class="form-check-label" for="paymentMethodPayPal">
                                                        <span class="fs-16 text-muted me-2"><i
                                                                class="ri-paypal-fill align-bottom"></i></span>
                                                        <span class="fs-13 text-wrap">Paypal</span>
                                                    </label>
                                                </div>
                                        </div> 
                                    </div>



                                    <div class="checkout-section mt-2" id="PaymentGatewayInputs">
                                       
                                    </div>

                                   

                                </div>
                            @endif
                            <!-- end tab pane -->
                            @if (Route::currentRouteName() == 'user.order.completed')
                                <div class="tab-pane fade  show active" id="pills-finish" role="tabpanel"
                                    aria-labelledby="pills-finish-tab">
                                    @include('user.checkout.partials.completed')
                                </div>
                            @endif

                            <!-- end tab pane -->
                        </div>
                        <!-- end tab content -->

                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->
            </div>
            <!-- end col -->
            <div class="col-xl-4">
                @include('user.checkout.partials.sidebar')

            </div>
            <!-- end col -->
        </div>
        <!-- end row -->
    @else
        <div class="text-center empty-cart" id="empty-cart">
            <div class="avatar-md mx-auto my-3">
                <div class="avatar-title bg-soft-info text-info fs-36 rounded-circle">
                    <i class='bx bx-cart'></i>
                </div>
            </div>
            <h5 class="mb-3">Your Cart is Empty!</h5>
            {{-- <a href="#" class="btn btn-success w-md mb-3">Shop Now</a> --}}
        </div>
    @endif


@endsection
@section('script')
    <script src="{{ URL::asset('assets/js/pages/ecommerce-product-checkout.init.js') }}"></script>

    <script>
        function removeItem(item_type, item_id) {
            $.ajax({
                url: "{{ route('user.cart.removeItem') }}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "item_type": item_type,
                    "item_id": item_id
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        location.reload();

                    } else {
                        toastr.error(response.message);
                        location.reload();
                        // Error message
                        console.log(response.message);
                    }
                },
                error: function(xhr) {
                    // Error message
                    location.reload();
                    console.log(xhr.responseText);
                }
            });
        }

        function removeAll(item_type, item_id) {
            $.ajax({
                url: "{{ route('user.cart.clearCart') }}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        location.reload();

                    } else {
                        toastr.error(response.message);
                        location.reload();
                        // Error message
                        console.log(response.message);
                    }
                },
                error: function(xhr) {
                    // Error message
                    location.reload();
                    console.log(xhr.responseText);
                }
            });
        }

        $(document).on('submit','#couponForm',function(e){
             e.preventDefault();

            var paymentMethod = $("input[name='paymentMethod']:checked").val();
            var formData = new FormData(this);
            formData.append('payment_method', paymentMethod);  // Add payment method to the data

             $.ajax({
                method:"POST",
                url:$(this).prop('action'),
                data:formData,
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if (data.success) {
                        toastr.success(data.message);       
                    } else {
                        toastr.error(data.message);
                    }
                    $('.sticky-side-div').replaceWith(data.sidebar_html);
                },
                error: function(error) {
                    console.error('There was an error!', error);
                    alert("There was an error. Please try again later.");
                    location.reload();
                }
            });
        })
    </script>

    <script>
        $(document).ready(function() {
            // Initial load - show/hide based on checked radio
            togglePaymentInputs();

            // Event listener to detect change
            $("input[name='paymentMethod']").change(function() {
                togglePaymentInputs();
            });
        });

        function togglePaymentInputs() { 
            var selectedPaymentMethod = $("input[name='paymentMethod']:checked").val();
            if ($("#paymentMethodStripe").is(":checked")) {
                $("#PaymentGatewayInputs").html(`@include('user.checkout.paymentgateways.stripe')`);
                initializeStripeCard();
            } else if ($("#paymentMethodAliPay").is(":checked")) {
                $("#PaymentGatewayInputs").html(`@include('user.checkout.paymentgateways.alipay')`);
            } else if ($("#paymentMethodPayPal").is(":checked")) {
                $("#PaymentGatewayInputs").html(`@include('user.checkout.paymentgateways.paypal')`);

            }
 
            $.ajax({
                method: "POST",
                url: "{{route('user.paymentgateway.changed')}}",  // Adjust the URL path as required
                data: {
                    payment_method: selectedPaymentMethod,
                    _token: "{{ csrf_token() }}"  // CSRF token for Laravel
                },
                success: function(response) {
                    if (response.success) {
                        $('.sticky-side-div').replaceWith(response.sidebar_html);
                    } else {
                        // Handle any unsuccessful response if needed
                    }
                },
                error: function(error) {
                    console.error('There was an error!', error);
                    //alert("There was an error. Please try again later.");
                }
            });
            
        }
        $(window).on('pageshow', function(event) {
            // If the page is loaded directly, or if it is loaded from the cache (via back/forward buttons)
            if (event.originalEvent.persisted) {
                // Force a reload if the page was loaded from the cache
                window.location.reload();
            } else {
                // Otherwise, ensure UI consistency with the existing form state
                togglePaymentInputs();
            }
        });
    </script>

@endsection
