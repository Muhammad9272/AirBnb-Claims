



<section class="">

        <div class="checkout-section mt-2">
            <div class="row">
                <div class="col-md-12">
                    <form method="post" action="{{route('user.alipay.checkout.store')}}">
                        @csrf
                        @include('includes.alerts')
                      
                            <div class="card-body11">

                               <p class="text-muted mt-2 fst-italic alert border-0 alert-info">
                                    By checking out, you agree with our <a target="_blank" href="{{route('front.page',\App\Models\Page::find(2)->slug)}}">Terms of Service</a> and confirm that 
                                    you have read our <a target="_blank" href="{{route('front.page',\App\Models\Page::find(3)->slug)}}">Privacy Policy</a>. You can cancel recurring payments at 
                                    any time.
                                </p>

                                <div class="d-flex align-items-start gap-3 mt-5">                                    
                                    <a href="{{route('user.cart')}}" class="btn btn-light btn-label " ><i class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i>Back to Shopping Cart</a>
                                    
                                    <button type="submit"  class="btn btn-primary btn-label right ms-auto"
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

