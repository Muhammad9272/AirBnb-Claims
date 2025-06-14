@extends('admin.layouts.master')

@section('title')
    Invoice #{{ $order->order_number }}
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-xxl-9">
            <div class="card" id="invoice-print">
                <div class="card-header border-bottom border-light">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">Invoice #{{ $order->order_number }}</h5>
                        </div>
                        <div class="col-auto">
                            <div class="btn-group">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-primary btn-sm">
                                    <i class="ri-arrow-left-line me-1"></i> Back to Details
                                </a>
                                <button id="downloadInvoice" class="btn btn-success btn-sm">
                                    <i class="ri-download-line me-1"></i> Download
                                </button>

                                <button onclick="window.print()" class="btn btn-info btn-sm">
                                    <i class="ri-printer-line me-1"></i> Print
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Header Section -->
                    <div class="row">
                        <div class="col-7">
                            <!-- Company Info -->
                            <div class="mb-4">
                                <img src="{{ URL::asset('assets/logo/logo-light.png') }}" alt="Logo" height="50">
                                <div class="mt-4">
                                    <h5 class="mb-1">{{ $gs->title }}</h5>
                                    <p class="mb-1">{{ $gs->office_address ?? 'No address specified' }}</p>
                                    <p class="mb-1">{{ $gs->email }}</p>
                                    <p class="mb-0">{{ $gs->phone }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-5 text-end">
                            <!-- Invoice Info -->
                            <div class="mb-4">
                                <h4 class="text-purple mb-3">INVOICE</h4>
                                <p class="mb-2">Status: <span class="fw-semibold">{{ ucfirst($order->status) }}</span></p>
                                <p class="mb-2">Invoice Date: <span class="fw-semibold">{{ $order->created_at->format('F d, Y') }}</span></p>
                                <p class="mb-0">Order ID: <span class="fw-semibold">#{{ $order->order_number }}</span></p>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <!-- Billing Address -->
                        <div class="col-6">
                            <div class="mb-4">
                                <h5 class="font-size-15">Billing Address:</h5>
                                <p class="mb-1">{{ $order->billing_name }}</p>
                                <p class="mb-1">{{ $order->billing_email }}</p>
                                <p class="mb-1">{{ $order->billing_phone }}</p>
                                <p class="mb-1">{{ $order->billing_address }}</p>
                                <p class="mb-1">{{ $order->billing_city }}, {{ $order->billing_state }} {{ $order->billing_zipcode }}</p>
                                <p>{{ $order->billing_country }}</p>
                            </div>
                        </div>
                        <!-- Shipping Address -->
                        <div class="col-6">
                            <div class="mb-4">
                                <h5 class="font-size-15">Shipping Address:</h5>
                                <p class="mb-1">{{ $order->shipping_name }}</p>
                                <p class="mb-1">{{ $order->shipping_email }}</p>
                                <p class="mb-1">{{ $order->shipping_phone }}</p>
                                <p class="mb-1">{{ $order->shipping_address }}</p>
                                <p class="mb-1">{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zipcode }}</p>
                                <p>{{ $order->shipping_country }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="table-responsive mt-4">
                        <table class="table table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th>Item</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                    <tr>
                                        <td>
                                            <h5 class="font-size-14 mb-1">{{ $item->product->name ?? 'Unknown Product' }}</h5>
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
                                        </td>
                                        <td class="text-end">{{ Helpers::formatPrice($item->price) }}</td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">{{ Helpers::formatPrice($item->price * $item->quantity) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Subtotal</th>
                                    <td class="text-end">{{ Helpers::formatPrice($order->subtotal) }}</td>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-end">Tax</th>
                                    <td class="text-end">{{ Helpers::formatPrice($order->tax) }}</td>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-end">Shipping</th>
                                    <td class="text-end">{{ Helpers::formatPrice($order->shipping) }}</td>
                                </tr>
                                @if($order->discount > 0)
                                <tr>
                                    <th colspan="3" class="text-end">Discount (Coupon)</th>
                                    <td class="text-end text-success">-{{ Helpers::formatPrice($order->discount) }}</td>
                                </tr>
                                @endif
                                @if($order->bits_discount > 0)
                                <tr>
                                    <th colspan="3" class="text-end">Bits Discount ({{ $order->bits_used }} Bits)</th>
                                    <td class="text-end text-success">-{{ Helpers::formatPrice($order->bits_discount) }}</td>
                                </tr>
                                @endif
                                <tr class="bg-light">
                                    <th colspan="3" class="text-end">Total</th>
                                    <td class="text-end fw-bold">{{ Helpers::formatPrice($order->total) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Totals -->
                    {{-- <div class="row justify-content-end mt-4">
                        <div class="col-sm-6">
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <th class="text-end">Subtotal:</th>
                                            <td class="text-end">{{ Helpers::formatPrice($order->subtotal) }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-end">Tax:</th>
                                            <td class="text-end">{{ Helpers::formatPrice($order->tax) }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-end">Shipping:</th>
                                            <td class="text-end">{{ Helpers::formatPrice($order->shipping) }}</td>
                                        </tr>
                                        @if($order->discount > 0)
                                        <tr>
                                            <th class="text-end">Discount (Coupon):</th>
                                            <td class="text-end text-success">-{{ Helpers::formatPrice($order->discount) }}</td>
                                        </tr>
                                        @endif
                                        @if($order->bits_discount > 0)
                                        <tr>
                                            <th class="text-end">Bits Discount ({{ $order->bits_used }} Bits):</th>
                                            <td class="text-end text-success">-{{ Helpers::formatPrice($order->bits_discount) }}</td>
                                        </tr>
                                        @endif
                                        <tr class="bg-light">
                                            <th class="text-end">Total:</th>
                                            <td class="text-end fw-bold">{{ Helpers::formatPrice($order->total) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
 --}}
                    <!-- Thank You Note -->
                    <div class="mt-5 text-center">
                        <p class="mb-0 text-muted">Thank you for your business!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <script>
    document.getElementById('downloadInvoice').addEventListener('click', async function () {
        const invoice = document.getElementById('invoice-print');

        // Use html2canvas to capture the invoice
        const canvas = await html2canvas(invoice, {
            scale: 2,
            useCORS: true
        });

        const imgData = canvas.toDataURL('image/png');
        const pdf = new jspdf.jsPDF('p', 'mm', 'a4');

        const imgProps = pdf.getImageProperties(imgData);
        const pdfWidth = pdf.internal.pageSize.getWidth();
        const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

        pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
        pdf.save("Invoice-{{ $order->order_number }}.pdf");
    });
</script>



@endsection

@section('style')
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #invoice-print, #invoice-print * {
            visibility: visible;
        }
        #invoice-print {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .card {
            border: none !important;
        }
        .card-header .btn-group {
            display: none !important;
        }
    }
</style>
@endsection


