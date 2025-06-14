<div class="card">
    <div class="card-header">
        <ul class="nav nav-tabs-custom card-header-tabs border-bottom-0" role="tablist">
            {{-- <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#top_selling_package"
                    role="tab">
                   Top Selling Package
                </a>
            </li>
            --}}
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#top_paying_customers"
                    role="tab">
                    Top Paying Customers
                </a>
            </li>
        </ul>
    </div>
    <!-- end card header -->
    <div class="card-body">
        <div class="tab-content">
           


            <!-- end tab-pane -->
            <div class="tab-pane active" id="top_paying_customers" role="tabpanel">    

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive table-card">
                            <table class="table table-borderless table-centered align-middle table-nowrap mb-0">
                                <thead class="text-muted bg-soft-light">
                                    <tr>
                                        <th>Customer Name</th>
                                        <th>Orders Quantity</th>
                                        <th>Total Orders Amount</th>
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    @if(isset($topPayingCustomers) && count($topPayingCustomers) > 0)
                                        @foreach($topPayingCustomers as $customer)
                                            @if($customer->user)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-2">
                                                            <img src="{!! Helpers::image($customer->user->photo, 'user/avatar/') !!}" alt="" class="avatar-xs rounded-circle">
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1"><a target="_blank" href="{{route('admin.users.show',$customer->user->id)}}">{{ $customer->user->name }}</a></h6>
                                                            <p class="text-muted mb-0"> {{ $customer->user->email }}</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $customer->ordersCount }}</td>
                                                <td>{{ Helpers::formatPrice($customer->total) }}</td>
                                            </tr><!-- end -->
                                            @endif
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="3" class="text-center py-4">
                                                <div class="avatar-md mx-auto mb-4">
                                                    <div class="avatar-title bg-light text-secondary rounded-circle fs-24">
                                                        <i class="ri-user-line"></i>
                                                    </div>
                                                </div>
                                                <h5 class="mb-1">No Customer Data Available</h5>
                                                <p class="text-muted">No purchase history found for analysis.</p>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody><!-- end tbody -->
                            </table><!-- end table -->
                        </div><!-- end tbody -->

                    </div>
                </div>

            </div>

            <!-- end tab pane -->
            <div class="tab-pane" id="top_selling_products" role="tabpanel">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Top Selling Products</h4>
                        <div class="flex-shrink-0">
                            <div class="dropdown card-header-dropdown">
                                <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="text-muted">All Time<i class="mdi mdi-chevron-down ms-1"></i></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="#">All Time</a>
                                    <a class="dropdown-item" href="#">This Month</a>
                                    <a class="dropdown-item" href="#">This Year</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        @if(isset($products) && count($products) > 0)
                            <div class="table-responsive">
                                <table class="table table-centered table-hover align-middle mb-0">
                                    <tbody>
                                        @foreach($products as $product)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 me-3">
                                                            <img src="{{ $product->product ? Helpers::image($product->product->main_image, 'products/') : asset('assets/images/placeholder-image.jpg') }}" 
                                                                alt="Product Image" class="avatar-sm rounded">
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1">{{ $product->product ? Str::limit($product->product->name, 30) : 'Unknown Product' }}</h6>
                                                            <p class="text-muted mb-0">
                                                                {{ $product->product && $product->product->category ? $product->product->category->name : 'Uncategorized' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end">
                                                    <span class="fw-semibold">{{ $product->total_quantity }} sold</span>
                                                    <p class="text-success mb-0">{{ Helpers::setCurrency($product->total_revenue) }}</p>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <div class="avatar-md mx-auto mb-4">
                                    <div class="avatar-title bg-light text-secondary rounded-circle fs-24">
                                        <i class="ri-shopping-bag-line"></i>
                                    </div>
                                </div>
                                <h5 class="mb-2">No Products Sold Yet</h5>
                                <p class="text-muted">There's no sales data available at the moment.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- end tab content -->
    </div>
    <!-- end card body -->
</div>