@if ($cartItems)
    @foreach ($cartItems as $cartItem)
        @php
            $itemDetails = App\CentralLogics\Cart::getItemDetails($cartItem['item_type'], $cartItem['item_id']);
        @endphp
        @if ($itemDetails)
            <div class="card ts-bg-gray-01 product border border-1 ts-rounded-06">
                <div class="card-body">
                    <div class="row gy-3 ">
                        {{-- <div class="col-sm-auto">
                            <div class="avatar-lg ">
                                <img src="{{ $itemDetails['photo'] }}" alt="" class="img-fluid d-block bg-light rounded p-1">
                            </div>
                        </div> --}}
                        <div class="col-md-8 d-flex">
                            <div class="avatar-lg me-3 ">
                                <img src="{{ $itemDetails['photo'] }}" alt="" class="img-fluid d-block bg-light rounded p-1">
                            </div>
                            <div class="">
                                <h5 class="fs-14 text-truncate1 d-block"><a href="ecommerce-product-detail" class="text-dark">
                                        {{ ucfirst($cartItem['item_type']) }}: {{ $itemDetails['name'] }}</a></h5>
                                <div class="d-block">
                                        @if($cartItem['item_type'] === 'interview')
                                        <p class="mb-0 pb-0">Booking Time:</p>
                                        {{ $cartItem['booking_date'] }}
                                        {{ $cartItem['booking_time'] }}
                                        @elseif($cartItem['item_type'] === 'events')
                                        <p class="mb-0 pb-0">Event Date:</p>
                                               {{ Carbon\Carbon::parse($itemDetails['event_date_time'])->format('F d, H:i A') }}
                                        @endif
                                </div>                               
                            </div>
                        </div>
                        <div class="col-md-4 d-flex gap-3 flex-sm-column justify-content-sm-between align-items-end">
                            <div class=" d-flex gap-2">

                                <h5 class="fs-16 mb-0">Item Price:</h5>
                                <h5 class="fs-16 text-primary mb-0">{{ Helpers::setCurrency($itemDetails['price']) }}
                                </h5>
                            </div>

                            <button type="button"
                                class="btn text-primary p-0 fs-16 ms-auto fw-bold text-decoration-underline"
                                onclick="removeItem('{{ $cartItem['item_type'] }}', {{ $cartItem['item_id'] }})">Remove</button>
                        </div>
                    </div>
                </div>
                <!-- card body -->
                {{-- <div class="card-footer">
                    <div class="row align-items-center gy-3">
                        <div class="col-sm">
                            <div class="d-flex flex-wrap my-n1">
                                <div>



                                    <a href="#"  class="d-block text-body p-1 px-2"
                               ><i
                                    class="ri-delete-bin-fill text-muted align-bottom me-1"></i>
                                Remove</a>

                                    <button type="button" class="btn btn-danger"
                                        onclick="removeItem('{{ $cartItem['item_type'] }}', {{ $cartItem['item_id'] }})">Remove</button>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-auto">
                            <div class="d-flex align-items-center gap-2 text-muted">
                                <div>Total :</div>
                                <h5 class="fs-14 mb-0"><span
                                        class="product-line-price">{{ Helpers::setCurrency($itemDetails['price']) }}</span>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div> --}}
                <!-- end card footer -->
            </div>
        @endif
    @endforeach
@endif
