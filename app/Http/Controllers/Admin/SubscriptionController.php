<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserSubscription;
use App\Models\User;
use DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Display subscription transactions
     *
     * @param  Request  $request
     * @return \Illuminate\View\View
     */
    public function transactions(Request $request)
    {
        $status = $request->input('status', 'all');
        
        return view('admin.subscriptions.transactions', compact('status'));
    }
    
    /**
     * Get subscription transactions data for DataTables
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function transactionsDatatables(Request $request)
    {
        $status = $request->input('status', 'all');
        
        $query = UserSubscription::with(['user', 'plan']);
        
        if ($status !== 'all') {
            $query = $query->where('status', $status);
        }
        
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('user', function (UserSubscription $subscription) {
                $user = $subscription->user;
                return $user ? '<div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h5 class="font-size-14 mb-0">' . $user->name . '</h5>
                                <p class="text-muted mb-0">' . $user->email . '</p>
                            </div>
                        </div>' : 'User not found';
            })
            ->addColumn('plan', function (UserSubscription $subscription) {
                $plan = $subscription->plan;
                return $plan ? '<div>
                            <h5 class="font-size-14 mb-0">' . $plan->name . '</h5>
                            <p class="text-muted mb-0">' . \App\CentralLogics\Helpers::setInterval($plan->interval) . '</p>
                        </div>' : 'Plan not found';
            })
            ->editColumn('price', function (UserSubscription $subscription) {
                return '$' . number_format($subscription->price, 2);
            })
            ->editColumn('created_at', function (UserSubscription $subscription) {
                return $subscription->created_at ? $subscription->created_at->format('M d, Y') : '-';
            })
            ->editColumn('ends_at', function (UserSubscription $subscription) {
                return $subscription->ends_at ? $subscription->ends_at->format('M d, Y') : '-';
            })
            ->editColumn('status', function (UserSubscription $subscription) {
                $statusClass = [
                    'active' => 'badge bg-success',
                    'canceled' => 'badge bg-danger',
                    'pending' => 'badge bg-warning',
                    'problem' => 'badge bg-info'
                ];
                $class = $statusClass[$subscription->status] ?? 'badge bg-secondary';
                return '<span class="' . $class . '">' . ucfirst($subscription->status) . '</span>';
            })
            ->addColumn('actions', function (UserSubscription $subscription) {
                return '<div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-info view-subscription" data-id="' . $subscription->id . '">
                                <i class="ri-eye-line"></i>
                            </button>
                        </div>';
            })
            ->rawColumns(['user', 'plan', 'status', 'actions'])
            ->make(true);
    }
    
    /**
     * Get subscription details for modal view
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function subscriptionDetail($id)
    {
        $subscription = UserSubscription::with(['user', 'plan'])->findOrFail($id);
        
        return view('admin.subscriptions.detail', compact('subscription'))->render();
    }
    
    /**
     * Export subscription transactions to CSV
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportTransactions(Request $request)
    {
        $status = $request->input('status', 'all');
        
        $query = UserSubscription::with(['user', 'plan']);
        
        if ($status !== 'all') {
            $query = $query->where('status', $status);
        }
        
        $subscriptions = $query->get();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="subscriptions_' . date('Y-m-d') . '.csv"',
        ];
        
        $callback = function() use ($subscriptions) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, [
                'ID', 'User Name', 'User Email', 'Plan', 'Amount', 'Payment Method', 
                'Status', 'Start Date', 'End Date', 'Transaction ID'
            ]);
            
            // Add rows
            foreach ($subscriptions as $subscription) {
                fputcsv($file, [
                    $subscription->id,
                    $subscription->user->name ?? 'N/A',
                    $subscription->user->email ?? 'N/A',
                    $subscription->plan->name ?? 'N/A',
                    $subscription->price,
                    $subscription->payment_method,
                    $subscription->status,
                    $subscription->created_at ? $subscription->created_at->format('Y-m-d') : 'N/A',
                    $subscription->ends_at ? $subscription->ends_at->format('Y-m-d') : 'N/A',
                    $subscription->transaction_id
                ]);
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }
}
