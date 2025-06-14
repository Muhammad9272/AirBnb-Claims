<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use App\Models\GeneralSetting;
use App\Models\User;
use App\Models\BitTransaction;
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use PDF;
use Cache;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        return view('admin.orders.index');
    }
    
    public function datatables(Request $request)
    {
        $query = Order::with('user')->orderBy('id', 'desc');

        // ✅ Apply backend filter for order status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        return DataTables::eloquent($query)
        ->addIndexColumn()
            ->addColumn('action', function(Order $data) {
                return '<div class="btn-group mb-1">
                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                        Action
                    </button>
                    <div class="dropdown-menu">
                        <a href="' . route('admin.orders.show', $data->id) . '"  class="dropdown-item">Details</a>
                        <a href="' . route('admin.orders.invoice', $data->id) . '"  class="dropdown-item">Invoice</a>
                        <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#deleteModal" data-href="' . route('admin.orders.delete', $data->id) . '" class="dropdown-item">Delete</a>
                    </div>
                </div>';
            })
            ->addColumn('user', fn($data) => $data->user->name ?? '-')
            ->addColumn('total', fn($data) => Helpers::formatPrice($data->total))
            ->addColumn('order_number', fn($data) => $data->order_number)
            ->addColumn('payment_status', function($data) {
                $class = match($data->payment_status) {
                    'pending' => 'warning',
                    'completed' => 'success',
                    default => 'danger'
                };
                return '<span class="badge bg-' . $class . '">' . $data->payment_status . '</span>';
            })
            ->addColumn('order_status', function($data) {
                $html = '<select class="form-select form-select-sm order-status-select" data-id="' . $data->id . '">';
                foreach(['pending', 'processing', 'completed', 'declined', 'cancelled'] as $s) {
                    $selected = $data->status === $s ? 'selected' : '';
                    $html .= '<option value="' . $s . '" ' . $selected . '>' . ucfirst($s) . '</option>';
                }
                $html .= '</select>';
                return $html;
            })
            ->addColumn('date', fn($data) => $data->created_at->format('d M Y'))
            ->rawColumns(['action', 'payment_status', 'order_status'])
            ->toJson();
    }

    public function show($id)
    {
        $order = Order::with('orderItems.product')->findOrFail($id);
        $gs = GeneralSetting::first();
        return view('admin.orders.show', compact('order', 'gs'));
    }
    
    public function invoice($id)
    {
        $order = Order::with('orderItems.product')->findOrFail($id);
        $gs = GeneralSetting::first();
        return view('admin.orders.invoice', compact('order', 'gs'));
    }

    public function downloadInvoice($id)
    {
        $order = Order::with('orderItems.product')->findOrFail($id);
        $gs = GeneralSetting::first();
        
        $pdf = PDF::loadView('admin.orders.pdf-invoice', compact('order', 'gs'));
        return $pdf->download('invoice-'.$order->order_number.'.pdf');
    }

    public function updateStatus(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $oldStatus = $order->status;
        $newStatus = $request->status;

        // Begin transaction to ensure all database operations succeed together
        DB::beginTransaction();
        
        try {
            // If order is being canceled
            if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
                // Restore bits if they were used
                if ($order->bits_used > 0) {
                    $user = User::findOrFail($order->user_id);
                    $user->bit_balance += $order->bits_used;
                    $user->save();
                    
                    // Record bit transaction
                    BitTransaction::create([
                        'user_id' => $order->user_id,
                        'amount' => $order->bits_used,
                        'description' => 'Refund for canceled order #'.$order->order_number,
                        'balance_after' => $user->bit_balance
                    ]);
                }
                
                // Restore stock quantities
                $this->restoreProductStock($order);
            }
            
            // If order is moving from pending/canceled to a processing state
            // if (($oldStatus === 'pending' || $oldStatus === 'cancelled') && in_array($newStatus, ['processing', 'completed', 'delivered'])) {
            //     // Deduct stock quantities if not already deducted
            //     $this->deductProductStock($order);
            // }
            
            // Update order status
            $order->status = $newStatus;
            $order->save();
            
            DB::commit();
            
            return response()->json(['success' => true, 'message' => 'Order status updated successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Restore product quantities back to stock when an order is cancelled
     *
     * @param Order $order
     * @return void
     */
    private function restoreProductStock($order)
    {
        foreach ($order->orderItems as $item) {
            if ($item->product) {
                $item->product->quantity += $item->quantity;
                $item->product->save();
                
                // Log stock change
                \Log::info('Stock restored: ' . $item->quantity . ' units for product ID ' . $item->product->id . ' after order #' . $order->order_number . ' cancellation');
            }
        }
    }

    /**
     * Deduct product quantities from stock when an order is processed
     *
     * @param Order $order
     * @return void
     */
    private function deductProductStock($order)
    {
        foreach ($order->orderItems as $item) {
            if ($item->product) {
                // Ensure not to have negative stock
                $newStock = max(0, $item->product->quantity - $item->quantity);
                // Check if stock is sufficient
                $newStock = max(0, $item->product->quantity - $item->quantity);
                if ($item->product->quantity < $item->quantity) {
                    $newStock = max(0, $item->product->quantity - $item->quantity);
                    \Log::warning('Insufficient stock: Only ' . $item->product->quantity . ' units available for product ID ' . $item->product->id . ' in order #' . $order->order_number);
                }
                
                $item->product->stock = $newStock;
                $item->product->save();
                
                // Log stock change
                \Log::info('Stock deducted: ' . $item->quantity . ' units for product ID ' . $item->product->id . ' for order #' . $order->order_number);
            }
        }
    }
    
    public function delete($id)
    {
        try {
            $order = Order::findOrFail($id);
             // Restore stock quantities
            $this->restoreProductStock($order);
            // Refund bits if order is being deleted and bits were used
            if ($order->bits_used > 0) {
                $user = $order->user;
                if ($user) {
                    $user->addBits(
                        $order->bits_used,
                        'order',
                        $order->id,
                        "Refunded bits for deleted order #{$order->order_number}"
                    );
                }
            }
            
            // Delete associated items and transaction
            $order->orderItems()->delete();
            if ($order->transaction) {
                $order->transaction->delete();
            }
            $order->delete();
            
            return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting order: '.$e->getMessage());
        }
    }
}