@extends('admin.layouts.master')

@section('title')
    Order Details #{{ $order->order_number }}
@endsection

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="me-4">
                        <h4 class="mb-1">Order #{{ $order->order_number }}</h4>
                        <p class="text-muted mb-0">{{ $order->created_at->format('F d, Y h:i A') }}</p>
                    </div>
                    <span class="badge bg-{{ $order->payment_status == 'completed' ? 'success' : 'warning' }} ms-2">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.orders.invoice', $order->id) }}" class="btn btn-primary btn-sm">
                        <i class="ri-file-text-line me-1"></i> View Invoice
                    </a>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Left Column -->
    <div class="col-lg-8">
        <!-- Address Cards Row -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Billing Address</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <th scope="row" width="200">Name</th>
                                        <td>{{ $order->billing_name }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Email</th>
                                        <td>{{ $order->billing_email }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Phone</th>
                                        <td>{{ $order->billing_phone }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Address</th>
                                        <td>{{ $order->billing_address }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">City</th>
                                        <td>{{ $order->billing_city }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">State</th>
                                        <td>{{ $order->billing_state }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Zipcode</th>
                                        <td>{{ $order->billing_zipcode }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Country</th>
                                        <td>{{ $order->billing_country }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Shipping Address</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <th scope="row" width="200">Name</th>
                                        <td>{{ $order->shipping_name }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Email</th>
                                        <td>{{ $order->shipping_email }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Phone</th>
                                        <td>{{ $order->shipping_phone }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Address</th>
                                        <td>{{ $order->shipping_address }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">City</th>
                                        <td>{{ $order->shipping_city }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">State</th>
                                        <td>{{ $order->shipping_state }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Zipcode</th>
                                        <td>{{ $order->shipping_zipcode }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Country</th>
                                        <td>{{ $order->shipping_country }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items Card -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Order Items</h5>
                <span class="badge bg-info">{{ $order->orderItems->count() }} Items</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-centered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
@foreach($order->orderItems as $item)
    <tr>
        <td>
            <div class="d-flex align-items-center">
                @if($item->product && $item->product->main_image)
                    <img src="{{ Helpers::image($item->product->main_image, 'products/') }}" 
                         alt="{{ $item->product->name ?? 'Product' }}" 
                         class="me-3 rounded" width="48">
                @endif
                <div>
                    <h5 class="font-size-14 text-truncate mb-1">
                        {{ $item->product->name ?? 'Unknown Product' }}
                    </h5>
                    
                    <!-- Add stock status indicator -->
                    @if($item->product)
                        @if($item->product->quantity <= 0)
                            <span class="badge bg-danger">Out of Stock</span>
                        @elseif($item->product->quantity < 5)
                            <span class="badge bg-warning">Low Stock: {{ $item->product->quantity }}</span>
                        @else
                            <span class="badge bg-success">In Stock: {{ $item->product->quantity }}</span>
                        @endif
                    @endif

                     @if($variations = $item->getFormattedVariations())
                        <div class="mt-1 space-y-1">
                            @foreach($variations as $variation)
                                <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                    <span class="font-medium">{{ $variation['name'] }}:</span>
                                    <span class="ml-2">{{ $variation['value'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </td>
        <td>{{ Helpers::formatPrice($item->price) }}</td>
        <td>{{ $item->quantity }}</td>
        <td class="text-end">{{ Helpers::formatPrice($item->price * $item->quantity) }}</td>
    </tr>
@endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-light1">
                <div class="row justify-content-end">
                    <div class="col-md-5">
                        <!-- Price Summary -->
                        <div class="table-responsive">
                            <table class="table table-sm text-end">
                                <tr>
                                    <td>Subtotal:</td>
                                    <td width="130">{{ Helpers::formatPrice($order->subtotal) }}</td>
                                </tr>
                                <tr>
                                    <td>Shipping:</td>
                                    <td>{{ Helpers::formatPrice($order->shipping) }}</td>
                                </tr>
                                <tr>
                                    <td>Tax:</td>
                                    <td>{{ Helpers::formatPrice($order->tax) }}</td>
                                </tr>
                                @if($order->discount > 0)
                                <tr class="text-success">
                                    <td>Discount (Coupon):</td>
                                    <td>-{{ Helpers::formatPrice($order->discount) }}</td>
                                </tr>
                                @endif
                                @if($order->bits_discount > 0)
                                <tr class="text-success">
                                    <td>Bits Discount ({{ $order->bits_used }} Bits):</td>
                                    <td>-{{ Helpers::formatPrice($order->bits_discount) }}</td>
                                </tr>
                                @endif
                                <tr class="fw-bold">
                                    <td>Total:</td>
                                    <td>{{ Helpers::formatPrice($order->total) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column -->
    <div class="col-lg-4">
        <!-- Order Status Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Order Status</h5>
            </div>
            <div class="card-body">
                <select class="form-select form-select-sm order-status-select w-100" data-id="{{ $order->id }}">
                    @foreach(['pending', 'processing', 'completed', 'declined', 'cancelled'] as $status)
                        <option value="{{ $status }}" {{ $order->status == $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Payment Info Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Payment Information</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th>Method:</th>
                            <td>{{ ucfirst($order->payment_method) }}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                <span class="badge bg-{{ $order->payment_status == 'completed' ? 'success' : ($order->payment_status == 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </td>
                        </tr>
                        @if($order->transaction)
                        <tr>
                            <th>Transaction ID:</th>
                            <td>{{ $order->transaction->transaction_id }}</td>
                        </tr>
                        <tr>
                            <th>Transaction Date:</th>
                            <td>{{ $order->transaction->created_at->format('M d, Y h:i A') }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        <!-- Customer Info Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Customer Information</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <th scope="row" width="200">Customer Name</th>
                                <td>{{ $order->user->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Email</th>
                                <td>{{ $order->user->email ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Phone</th>
                                <td>{{ $order->user->phone ?? 'N/A' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Order Summary Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Order Summary</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm text-end">
                        <tr>
                            <td>Subtotal:</td>
                            <td width="130">{{ Helpers::formatPrice($order->subtotal) }}</td>
                        </tr>
                        <tr>
                            <td>Shipping:</td>
                            <td>{{ Helpers::formatPrice($order->shipping) }}</td>
                        </tr>
                        <tr>
                            <td>Tax:</td>
                            <td>{{ Helpers::formatPrice($order->tax) }}</td>
                        </tr>
                        @if($order->discount > 0)
                        <tr class="text-success">
                            <td>Discount (Coupon):</td>
                            <td>-{{ Helpers::formatPrice($order->discount) }}</td>
                        </tr>
                        @endif
                        @if($order->bits_discount > 0)
                        <tr class="text-success">
                            <td>Bits Discount ({{ $order->bits_used }} Bits):</td>
                            <td>-{{ Helpers::formatPrice($order->bits_discount) }}</td>
                        </tr>
                        @endif
                        <tr class="fw-bold">
                            <td>Total:</td>
                            <td>{{ Helpers::formatPrice($order->total) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('.order-status-select').on('change', function() {
            var status = $(this).val();
            var orderId = $(this).data('id');
            
            // Show confirmation for certain status changes
            if (status === 'cancelled' || status === 'declined') {
                if (!confirm('Are you sure you want to change the status to ' + status + '?')) {
                    // Reset to previous value
                    $(this).val($(this).find('option[selected]').val());
                    return;
                }
            }
            
            $.ajax({
                url: '{{ route('admin.orders.update-status') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    order_id: orderId,
                    status: status
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success('Order status updated successfully');
                    } else {
                        toastr.error('Failed to update order status');
                    }
                },
                error: function() {
                    toastr.error('An error occurred while updating status');
                }
            });
        });
    });
</script>
@endsection
