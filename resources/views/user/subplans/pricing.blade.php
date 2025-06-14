@extends('user.layouts.master')
@section('meta_title')
    @lang('translation.pricing')
@endsection
@section('content')


 


<div class="subplans_cards fx-bg" style="background-image:url({{ asset('assets/images/subtract2.png') }});">


    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18"> Pricing</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{route('user.dashboard')}}">Home</a></li> 
                                           
                        <li class="breadcrumb-item active">Pricing</li>
                        
                    </ol>
                </div>

            </div>
        </div>
    </div>   

    <div class="row justify-content-center mt-4 "  >
        <div class="col-lg-5">
            <div class="text-center mb-4">
                <h4 class="fw-semibold fs-22">Your Plan, Your Way</h4>
                {{-- <p class="text-muted mb-4 fs-15">Only Need To Pay Plans' Price Differences to upgrade your membership</p> --}}
                {{-- <i data-toggle="tooltip" data-placement="top" title="Hooray!" class=" ri-information-line"></i>  --}}
                 <p class="text-muted mb-4 fs-15"> Pay just the difference when you switch from "Basic" to "Essential" or "Premium."</p>

                 <!-- Nav tabs -->
                <div class="d-inline-flex">
                    <ul class="nav nav-pills arrow-navtabs plan-nav rounded mb-3 p-1" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-semibold active" id="regulartb-tab" data-bs-toggle="pill" data-bs-target="#regularPlans" type="button" role="tab" aria-selected="true">Regular Plans</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-semibold" id="svip-tab" data-bs-toggle="pill" data-bs-target="#svipPlan" type="button" role="tab" aria-selected="false">SVIP Plan <span class="badge bg-warning">Limited</span></button>
                        </li>
                    </ul>
                </div>
               
            </div>


        </div>
        <!--end col-->
    </div>


    <!--end row-->


    <div class="row justify-content-center m-3">
        <div class="col-xxl-11">
           


            <!-- Tab panes -->
            <div class="tab-content">
                <!-- Regular Plans Tab -->
                <div id="regularPlans" class="container tab-pane active">
                    <div class="row d-flex">
                        <!--end col-->
                        @foreach ($subplans->take(3) as $data)                
                            @php
                            $activeSubscription = auth()->user()->subscriptionsActive();
                            @endphp
                            <div class="col-xl-4  col-sm-6 d-flex flex-column">
                                <div class="card pricing-box ribbon-box right h-100 rounded-4 apt-box-shadow1 {{$data->is_featured?'featured_sub_plan fx-bg':''}}"  @if($data->is_featured)style="background-image:url({{ asset('assets/images/subtract2.png') }});" @endif>
                                    
                                    <div class="card-body p-4 m-2 d-flex flex-column justify-content-around11 " >
                                        @if($data->is_featured)
                                        <div class="position-absolute featured__thumb_image">
                                            <img src="{{ asset('assets/images/featured.png') }}">
                                        </div>
                                        {{-- <div class="ribbon-two ribbon-two-warning"><span>Popular</span></div> --}}
                                        @endif
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1">
                                                    <h2 class="mb-1 fw-semibold">{{ $data->name }}</h2>
                                                </div>
                                            </div>

                                            <div class="pt-4">
                                            <div class="d-flex">
                                                @php
                                                 $previous_Plan=App\CentralLogics\Cart::getPreviousPlan($data->id);
                                                @endphp
                                                @if ($previous_Plan['subscription_discounted_price']>0 && isset($activeSubscription) && ($activeSubscription->subplan) && $activeSubscription->subplan->id!=$data->id)
                                                    <h1 class="month mb-0 fw-bold">
                                                        {{ Helpers::setCurrency( $data->price-$previous_Plan['subscription_discount']) }}
                                                    </h1>
                                                    <h3 class="text-danger"><del> {{ Helpers::setCurrency( $data->price) }}</del></h3>
                                                @else
                                                <h1 class="month mb-0 fw-bold">
                                                    {{ Helpers::setCurrency( $data->price) }}
                                                </h1>
                                                @endif
                                            </div>
                                                

                                                <p class=" mb-0">
                                                        {{ Helpers::setInterval($data->interval) }} 
                                                </p>
                                            </div>
                                       
                                            <div class="mt-4 mb-5">
                                                <div class="row row-cols-1">
                                                    <div class="pe-0">
                                                       
                                                        @if (isset($activeSubscription) && ($activeSubscription->subplan) && $activeSubscription->subplan->id==$data->id)
                                                            <button type="button"
                                                                class="btn  {{$data->is_featured?' bg-white':'btn-warning'}}  d-flex align-items-center justify-content-center gap-1 w-100 waves-effect waves-light rounded-3 fs-17 fw-bold border-0" disabled>
                                                                Active Plan
                                                            </button>

                                                            
                                                        @else
                                                            <form method="POST" action="{{ route('user.cart.addItem') }}">
                                                                @csrf
                                                                <input type="hidden" name="item_type" value="subscription_plan">

                                                                <input type="hidden" name="item_id" value="{{ $data->id }}">
                                                                <button type="submit"
                                                                    class="btn btn-{{$data->is_featured?' apt-btn-outline-light':'warning'}} d-flex align-items-center justify-content-center gap-1 w-100 waves-effect waves-light rounded-3 fs-17 fw-bold">Buy
                                                                    Now 
                                                                    {{-- <i class=" ri-arrow-right-s-line"></i> --}}
                                                                </button>
                                                            </form>
                                                            
                                                        @endif
                                                    </div>

                                                </div>
                                            </div>
                                           
                                        
                                            <ul class="list-unstyled vstack gap-3 text-muted">
                                                <?php $arr = json_decode($data->features); ?>
                                                @foreach ($subfeatures as $key => $subfeature)
                                                    @if (in_array($subfeature->id, $arr))
                                                        <li>
                                                            <div class="d-flex">
                                                             
                                                                <div class="flex-shrink-0 text-{{$data->is_featured?'white':'primary'}} me-1">
                                                                    <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                                                </div>
                                                                <div class="flex-grow-1 feat">
                                                                    {{ $subfeature->name }}
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @else
                                                        <li>
                                                            <div class="d-flex">
                                                                <div class="flex-shrink-0 text-default me-1">
                                                                    <i class="ri-close-circle-fill fs-15 align-middle"></i>
                                                                </div>
                                                                <div class="flex-grow-1 feat">
                                                                    {{ $subfeature->name }}
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endif
                                                @endforeach


                                            </ul>
                                        </div>

                                       

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- SVIP Plan Tab -->
                <div id="svipPlan" class="container tab-pane fade">               
                    <div class="row d-flex">
                        <!--end col-->
                        @foreach ($subplans->skip(3) as $data)                
                            @php
                            $activeSubscription = auth()->user()->subscriptionsActive();
                            @endphp
                            <div class="col-xl-4  col-sm-6 d-flex flex-column m-auto">
                                <div class="card pricing-box ribbon-box right h-100 rounded-4 apt-box-shadow1 {{$data->is_featured?'featured_sub_plan fx-bg':''}}"  @if($data->is_featured)style="background-image:url({{ asset('assets/images/subtract2.png') }});" @endif>
                                    
                                    <div class="card-body p-4 m-2 d-flex flex-column justify-content-around11 " >
                                        @if($data->is_featured)
                                        <div class="position-absolute featured__thumb_image">
                                            <img src="{{ asset('assets/images/featured.png') }}">
                                        </div>
                                        {{-- <div class="ribbon-two ribbon-two-warning"><span>Popular</span></div> --}}
                                        @endif
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1">
                                                    <h2 class="mb-1 fw-semibold">{{ $data->name }}</h2>
                                                </div>
                                            </div>

                                            <div class="pt-4">
                                            <div class="d-flex">
                                                @php
                                                 $previous_Plan=App\CentralLogics\Cart::getPreviousPlan($data->id);
                                                @endphp
                                                @if ($previous_Plan['subscription_discounted_price']>0 && isset($activeSubscription) && ($activeSubscription->subplan) && $activeSubscription->subplan->id!=$data->id)
                                                    <h1 class="month mb-0 fw-bold">
                                                        {{ Helpers::setCurrency( $data->price-$previous_Plan['subscription_discount']) }}
                                                    </h1>
                                                    <h3 class="text-danger"><del> {{ Helpers::setCurrency( $data->price) }}</del></h3>
                                                @else
                                                <h1 class="month mb-0 fw-bold">
                                                    {{ Helpers::setCurrency( $data->price) }}
                                                </h1>
                                                @endif
                                            </div>
                                                

                                                <p class=" mb-0">
                                                        {{ Helpers::setInterval($data->interval) }} 
                                                </p>
                                            </div>
                                       
                                            <div class="mt-4 mb-5">
                                                <div class="row row-cols-1">
                                                    <div class="pe-0">
                                                       
                                                        @if (isset($activeSubscription) && ($activeSubscription->subplan) && $activeSubscription->subplan->id==$data->id)
                                                            <button type="button"
                                                                class="btn  {{$data->is_featured?' bg-white':'btn-warning'}}  d-flex align-items-center justify-content-center gap-1 w-100 waves-effect waves-light rounded-3 fs-17 fw-bold border-0" disabled>
                                                                Active Plan
                                                            </button>

                                                            
                                                        @else
                                                            <form method="POST" action="{{ route('user.cart.addItem') }}">
                                                                @csrf
                                                                <input type="hidden" name="item_type" value="subscription_plan">

                                                                <input type="hidden" name="item_id" value="{{ $data->id }}">
                                                                <button type="submit"
                                                                    class="btn btn-{{$data->is_featured?' apt-btn-outline-light':'warning'}} d-flex align-items-center justify-content-center gap-1 w-100 waves-effect waves-light rounded-3 fs-17 fw-bold">Buy
                                                                    Now 
                                                                    {{-- <i class=" ri-arrow-right-s-line"></i> --}}
                                                                </button>
                                                            </form>
                                                            
                                                        @endif
                                                    </div>

                                                </div>
                                            </div>
                                           
                                        
                                            <ul class="list-unstyled vstack gap-3 text-muted">
                                                <?php $arr = json_decode($data->features); ?>
                                                @foreach ($subfeatures as $key => $subfeature)
                                                    @if (in_array($subfeature->id, $arr))
                                                        <li>
                                                            <div class="d-flex">
                                                             
                                                                <div class="flex-shrink-0 text-{{$data->is_featured?'white':'primary'}} me-1">
                                                                    <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                                                </div>
                                                                <div class="flex-grow-1 feat">
                                                                    {{ $subfeature->name }}
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @else
                                                        <li>
                                                            <div class="d-flex">
                                                                <div class="flex-shrink-0 text-default me-1">
                                                                    <i class="ri-close-circle-fill fs-15 align-middle"></i>
                                                                </div>
                                                                <div class="flex-grow-1 feat">
                                                                    {{ $subfeature->name }}
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endif
                                                @endforeach


                                            </ul>
                                        </div>

                                       

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--end row-->
</div>

    <!--end row-->
@endsection
@section('script')
    <script src="{{ URL::asset('assets/js/pages/pricing.init.js') }}"></script>
{{-- <script>
$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();
});
</script> --}}
@endsection
