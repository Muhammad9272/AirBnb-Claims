

@pushOnce('partial_css')
<script src="https://js.stripe.com/v3/"></script>
<link href="{{ URL::asset('assets/common_assets/css/payment.css')}}" rel="stylesheet" type="text/css" />
<style type="text/css">
	.d-done{
		display:none;
	}
</style>
@endPushOnce

<section class="">

        <div class="checkout-section mt-2">
            <div class="row">
                <div class="col-md-12">
                    <form id="stripeForm" method="post" action="{{route('user.checkout.store')}}">
                        @csrf
                        @include('includes.alerts')
                      
                            <div class="card-body11">
                                <h4>Billing details</h4>
                                <p><strong>Credit card</strong><br>
                                </p>                              
                                {{-- <input type="hidden" name="payment_gateway_name"
                                    value="{{$request->payment_gateway_name}}"> --}}
                                @if (auth()->user()->hasStripeId())
                                <input type="hidden" name="payment_method" value="{{auth()->user()->defaultPaymentMethod()->id}}">
                                <div class="card mb-4 " id="savedCard"
                                    style="border: 1px solid #2a2f34;border-radius: 5px;">
                                    <div class="card-body d-flex justify-content-between align-items-start">
                                        <p class="card-text">

                                            <img src="{{ asset('/images/payments/brands/'.strtolower(auth()->user()->pm_type).'.svg')}}"
                                                class="mr-1">
                                            <strong class="text-capitalize">{{ auth()->user()->pm_type }}</strong> <br>
                                            •••• •••• •••• {{ auth()->user()->pm_last_four }}
                                        </p>
                                        <a href="javascript:;" class="btn g2z-btn-primary"
                                            id="changesavedCard">Change</a>
                                    </div>
                                </div>
                                @endif
                                <div class="{{auth()->user()->hasStripeId()?'d-done':''}}" id="addnewCard">
                                    <div class="mb-3">
                                        <div class="col-md-12 mb-2">
                                                <label for="cc-name" class="form-label">Name on
                                                    card</label>
                                                <input type="text" class="form-control" id="cc-name"
                                                    placeholder="Enter name" style="padding: 12px;" {{auth()->user()->hasStripeId()?'':'required'}}>
                                                <small class="text-muted ">Full name as displayed on
                                                    card</small>
                                        </div>
                                        <div class="col-lg-12 col-md-12">
                                        	 <label for="" class="form-label">Card Details</label>
                                            <div id="card-element"></div>
                                        </div>
                                        <div id="card-errors" role="alert"></div>

                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" value="1" id="save_card_details"
                                            name="save_card_details">
                                        <label class="form-check-label" for="save_card_details"><small>Save Card For
                                                Future Purchases</small></label>
                                    </div>
                                </div>

                                <p class="text-muted mt-2 fst-italic alert border-0 alert-info">
                                    By checking out, you agree with our <a target="_blank" href="{{route('front.page',\App\Models\Page::find(2)->slug)}}">Terms of Service</a> and confirm that 
                                    you have read our <a target="_blank" href="{{route('front.page',\App\Models\Page::find(3)->slug)}}">Privacy Policy</a>. You can cancel recurring payments at 
                                    any time.
                                </p>


                                <div class="d-flex align-items-start gap-3 mt-5">                                    
                                    <a href="{{route('user.cart')}}" class="btn btn-light btn-label " ><i class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i>Back to Shopping Cart</a>
                                    
                                    <button type="submit" id="{{auth()->user()->hasStripeId()?'':'card-button'}}" class="btn btn-primary btn-label right ms-auto paybtnn"
                                        ><i
                                            class="ri-shopping-basket-line label-icon align-middle fs-16 ms-2"></i>Complete
                                        Order</button>
                                </div>
                                

                            </div>
                        
                    </form>
                </div>
            </div>

        </div>

</section>



@pushOnce('partial_script')

<script type="text/javascript">
    // var full_name_user = "{{Auth::user()->name}}";
    var key_stripe = "{{ env('STRIPE_KEY') }}";
    var payment_card_error = "An error has occurred, try again";

    $(document).ready(function () {
        $(document).on('click', '#changesavedCard', function () {
            $('#savedCard').hide();
            $('#addnewCard').show();
            $("input[name='payment_method']").remove();
            $("#cc-name").attr("required",true);
            $('.paybtnn').attr('id', 'card-button');
            // Show new card fields
        });
    })
</script>

<script src="{{ asset('assets/common_assets/js/add-payment-card.js') }}"></script>
@endPushOnce