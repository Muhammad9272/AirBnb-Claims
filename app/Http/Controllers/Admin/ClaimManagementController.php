<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Claim;
use App\Models\ClaimComment;
use App\Models\ClaimStatusHistory;
use App\Models\User;
use App\Models\GeneralSetting;
use App\Models\InfluencerCommission;
use DataTables;
use Carbon\Carbon;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ClaimManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of claims.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $status = $request->status ?? 'all';
        
        // Get statistics for the dashboard
        $statistics = [
            'total' => Claim::count(),
            'pending' => Claim::where('status', 'pending')->count(),
            'under_review' => Claim::where('status', 'under_review')->count(),
            'approved' => Claim::where('status', 'approved')->count(),
            'rejected' => Claim::where('status', 'rejected')->count(),
            'amount_claimed' => Claim::sum('amount_requested'),
            'amount_approved' => Claim::where('status', 'approved')->sum('amount_approved'),
        ];
        
        return view('admin.claims.index', compact('status', 'statistics'));
    }

    /**
     * Return claim data for DataTables.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function datatables(Request $request)
    {
        $status = $request->status ?? 'all';
        
        $query = Claim::with('user');
        
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('user_info', function (Claim $claim) {
                return $claim->user ? '<div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h5 class="font-size-14 mb-0">' . $claim->user->name . '</h5>
                                <p class="text-muted mb-0">' . $claim->user->email . '</p>
                            </div>
                        </div>' : 'User not found';
            })
            ->editColumn('amount_requested', function (Claim $claim) {
                return '$' . number_format($claim->amount_requested, 2);
            })
            ->editColumn('amount_approved', function (Claim $claim) {
                return $claim->amount_approved ? '$' . number_format($claim->amount_approved, 2) : '-';
            })
            ->editColumn('created_at', function (Claim $claim) {
                return $claim->created_at->format('M d, Y');
            })
            ->editColumn('updated_at', function (Claim $claim) {
                return $claim->updated_at->format('M d, Y');
            })
            ->addColumn('action', function (Claim $claim) {
                return '<div class="d-flex gap-2">
                            <a href="' . route('admin.claims.show', $claim->id) . '" class="btn btn-primary btn-sm">
                                <i class="ri-eye-line"></i>
                            </a>
                        </div>';
            })
            ->rawColumns(['user_info', 'action'])
            ->make(true);
    }

    /**
     * Display the specified claim.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $claim = Claim::with(['user', 'user.activeuserSubscriptions.plan', 'comments.user', 'evidence', 'statusHistory.user'])
            ->findOrFail($id);
        
        return view('admin.claims.show', compact('claim'));
    }

    /**
     * Update claim status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,under_review,approved,rejected',
            'approved_amount' => 'required_if:status,approved|nullable|numeric|min:0',
            'rejection_reason' => 'required_if:status,rejected|nullable|string',
            'comment' => 'nullable|string',
        ]);
        
        $claim = Claim::findOrFail($id);
        $oldStatus = $claim->status;
        $newStatus = $request->status;
        
        // Start database transaction
        DB::beginTransaction();
        
        try {
            $claim->status = $newStatus;
            if ($newStatus === 'approved') {
                $claim->amount_approved = $request->approved_amount;
                
                $activeSubscription = $claim->user->activeuserSubscriptions()->first();
                if ($activeSubscription && $activeSubscription->plan) {
                    $commissionRate = $activeSubscription->plan->commission_percentage ?? 0;
                    $claim->commission_amount = ($request->approved_amount * $commissionRate) / 100;
                    
                    $isCommissionAlreadyPaid = $claim->is_commission_paid && $claim->payment_id;
                    
                    if (!$isCommissionAlreadyPaid) {
                        $user = User::find($claim->user_id);
                        if($user && isset($user->stripe_customer_id)) {
                            Stripe::setApiKey(config('services.stripe.secret'));
                           
                            try {
                                $paymentIntent = PaymentIntent::create([
                                    'amount' => (int)($claim->commission_amount * 100),
                                    'currency' => 'usd',
                                    'customer' => $user->stripe_customer_id,
                                    'payment_method' => $user->stripe_payment_method_id,
                                    'off_session' => true,
                                    'confirm' => true,
                                    'description' => floatval($activeSubscription->plan->commission_percentage).'% commission for claim #' . $claim->id,
                                ]);

                                if ($paymentIntent->status === 'succeeded') {
                                    $claim->is_commission_paid = true;
                                    $claim->payment_id = $paymentIntent->id;
                                    
                                    Log::info('Commission charged successfully', [
                                        'claim_id' => $claim->id,
                                        'payment_intent' => $paymentIntent->id,
                                        'amount' => $claim->commission_amount,
                                    ]);
                                }
                            } catch (\Exception $paymentException) {
                                $errorMessage = $paymentException->getMessage();
                                Log::error('Commission payment failed', [
                                    'claim_id' => $claim->id,
                                    'error' => $errorMessage,
                                    'user_id' => $user->id,
                                ]);
                                $user->update([
                                    'stripe_payment_method_id' => null
                                ]);
                                DB::commit();
                                return redirect()->back()->with('error', 'User Payment card is invalid or has been declined.');
                            }
                        }
                    } else {
                        Log::info('Commission already paid - skipping charge', [
                            'claim_id' => $claim->id,
                            'payment_id' => $claim->payment_id,
                            'amount' => $claim->commission_amount,
                        ]);
                    }
                }
            }

            if ($newStatus === 'rejected') {
                $claim->rejection_reason = $request->rejection_reason;
                $claim->is_commission_paid = false;
                $claim->payment_id = null;
            }
            
            $claim->save();
            
            if($newStatus === 'approved' || $newStatus === 'rejected'){
                // Update influencer commission based on new status
                $this->updateInfluencerCommission($claim, $newStatus);
            }
            
            // Add status history
            ClaimStatusHistory::create([
                'claim_id' => $claim->id,
                'user_id' => Auth::id(),
                'from_status' => $oldStatus,
                'to_status' => $newStatus,
                'notes' => $request->comment
            ]);
            
            // Add comment if provided
            if ($request->filled('comment')) {
                ClaimComment::create([
                    'claim_id' => $claim->id,
                    'user_id' => Auth::id(),
                    'comment' => $request->comment,
                    'is_admin' => true
                ]);
            }
            
            // Send notification to user about status change
            if ($oldStatus !== $newStatus) {
                NotificationService::claimStatusChanged($claim, $oldStatus, $newStatus, $request->comment);
            }
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Claim status updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update claim status', [
                'claim_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Failed to update claim status. ' . $e->getMessage());
        }
    }

    /**
     * Add a comment to a claim.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function addComment(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string'
        ]);
        
        $claim = Claim::findOrFail($id);
        $comment = new ClaimComment();
        $comment->claim_id = $claim->id;
        $comment->user_id = Auth::id();
        $comment->comment = $request->content;
        $comment->is_admin = true;
        $comment->save();
        
        NotificationService::newComment($comment);

        try {
            $recipient = $claim->user;
            if ($recipient && !empty($recipient->email) && !$comment->is_private) {
                $subject = "New comment on your claim #" . ($claim->claim_number ?? $claim->id);
                $claimUrl = url('user/claims/' . $claim->id);
                $body = "<p>Hi " . e($recipient->name ?? 'User') . ",</p>";
                $body .= "<p>An admin has commented on your claim <strong>#" . e($claim->claim_number ?? $claim->id) . "</strong>:</p>";
                $body .= "<blockquote style=\"border-left:4px solid #ccc;padding-left:8px;\"> " . nl2br(e($comment->comment)) . " </blockquote>";
                $body .= "<p>You can view the claim here: <a href=\"" . $claimUrl . "\">View Claim</a></p>";
                $body .= "<p>Regards,<br/>" . e(config('app.name')) . "</p>";

                Mail::send([], [], function ($message) use ($recipient, $subject, $body) {
                $message->to($recipient->email, $recipient->name ?? null)
                            ->subject($subject)
                            ->html($body);
                });
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send claim comment email: ' . $e->getMessage(), [
                'claim_id' => $claim->id,
                'comment_id' => $comment->id
            ]);
        }
        
        return redirect()->back()->with('success', 'Comment added successfully');
    }

    /**
     * Get claims by user.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function userClaims($userId)
    {
        $user = User::findOrFail($userId);
        $claims = Claim::where('user_id', $userId)->latest()->paginate(10);
        
        return view('admin.claims.user-claims', compact('user', 'claims'));
    }

    /**
     * Export claims to CSV.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Request $request)
    {
        $status = $request->status ?? 'all';
        
        $query = Claim::with('user');
        
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        $claims = $query->get();
        
        $filename = 'claims_export_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($claims) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'ID', 'Claim Number', 'User', 'Email', 'Title', 
                'Amount Requested', 'Amount Approved', 'Status', 
                'Check-in Date', 'Check-out Date', 'Incident Date',
                'Created At'
            ]);
            
            foreach ($claims as $claim) {
                fputcsv($file, [
                    $claim->id,
                    $claim->claim_number,
                    $claim->user->name ?? 'Unknown',
                    $claim->user->email ?? 'Unknown',
                    $claim->title,
                    $claim->amount_requested,
                    $claim->amount_approved,
                    $claim->status,
                    $claim->check_in_date?->format('Y-m-d') ?? 'N/A',
                    $claim->check_out_date?->format('Y-m-d') ?? 'N/A',
                    $claim->incident_date?->format('Y-m-d') ?? 'N/A',
                    $claim->created_at->format('Y-m-d H:i:s')
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Generate reports for claims.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reports(Request $request)
    {
        $period = $request->period ?? 'month';
        $status = $request->status ?? 'all';
        
        // Determine date range based on period
        $endDate = Carbon::now();
        $startDate = match($period) {
            'week' => Carbon::now()->subWeek(),
            'month' => Carbon::now()->subMonth(),
            'quarter' => Carbon::now()->subMonths(3),
            'year' => Carbon::now()->subYear(),
            default => Carbon::now()->subMonth()
        };
        
        // Build query
        $query = Claim::whereBetween('created_at', [$startDate, $endDate]);
        
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        // Get data for reports
        $claims = $query->get();
        
        $reportData = [
            'total_claims' => $claims->count(),
            'total_amount' => $claims->sum('amount_requested'),
            'approved_amount' => $claims->where('status', 'approved')->sum('amount_approved'),
            'commission_earned' => $claims->sum('commission_amount'),
            'status_breakdown' => [
                'pending' => $claims->where('status', 'pending')->count(),
                'in_review' => $claims->where('status', 'under_review')->count(),
                'pending_evidence' => 0, // Not in your schema but appears in views
                'approved' => $claims->where('status', 'approved')->count(),
                'rejected' => $claims->where('status', 'rejected')->count(),
                'paid' => 0, // Not in your schema but appears in views
            ],
        ];
        
        return view('admin.claims.reports', compact('reportData', 'startDate', 'endDate', 'period', 'status'));
    }

    /**
     * Update influencer commission when claim status changes
     *
     * @param  Claim  $claim
     * @param  string  $newStatus
     * @return void
     */
    private function updateInfluencerCommission($claim, $newStatus)
    {
        try {
            // Find existing commission record for this claim
            $commission = InfluencerCommission::where('claim_id', $claim->id)->first();
            
            if (!$commission) {
                return; // No commission record exists
            }

            if ($newStatus === 'approved') {
                // Get general settings for commission validation
                $gs = GeneralSetting::first();
                
                if (!$gs || !$gs->influencer_commission_percentage) {
                    $commission->update([
                        'status' => 'rejected',
                        'notes' => ($commission->notes ?? '') . "\n[" . now()->format('Y-m-d H:i:s') . "] Commission rejected: Influencer commission is disabled in settings."
                    ]);
                    return;
                }

                // Verify referrer is still an influencer
                $referrer = User::find($commission->influencer_user_id);
                if (!$referrer || $referrer->role_type !== 'influencer') {
                    $commission->update([
                        'status' => 'rejected',
                        'commission_amount' => 0,
                        'notes' => ($commission->notes ?? '') . "\n[" . now()->format('Y-m-d H:i:s') . "] Commission rejected: Referrer is no longer an influencer or account not found."
                    ]);
                    
                    \Log::info('Commission rejected - referrer not an influencer', [
                        'commission_id' => $commission->id,
                        'claim_id' => $claim->id,
                        'referrer_id' => $commission->influencer_user_id
                    ]);
                    return;
                }

                if ($gs->influencer_commission_duration_days) {
                    $claimCreationDate = $claim->created_at;
                    $commissionEndDate = $claimCreationDate->copy()->addDays($gs->influencer_commission_duration_days);
                    $now = now();
                    
                    if ($now->greaterThan($commissionEndDate)) {
                        // Commission period expired - reject the commission
                        $commission->update([
                            'status' => 'rejected',
                            'commission_amount' => 0,
                            'notes' => ($commission->notes ?? '') . "\n[" . now()->format('Y-m-d H:i:s') . "] Commission rejected: Claim approved after commission period expired. Claim created on {$claimCreationDate->format('Y-m-d')}, commission period ({$gs->influencer_commission_duration_days} days) ended on {$commissionEndDate->format('Y-m-d')}."
                        ]);

                        \Log::info('Influencer commission rejected - period expired at approval', [
                            'commission_id' => $commission->id,
                            'claim_id' => $claim->id,
                            'claim_created' => $claimCreationDate,
                            'claim_approved' => $now,
                            'commission_end_date' => $commissionEndDate
                        ]);
                        return;
                    }
                }

                // Recalculate commission based on APPROVED amount (not requested)
                $finalCommission = ($claim->amount_approved * $gs->influencer_commission_percentage) / 100;
                $finalCommission = round($finalCommission, 2);

                // Prepare approval notes
                $approvalNotes = ($commission->notes ?? '') . "\n[" . now()->format('Y-m-d H:i:s') . "] Commission approved. ";
                $approvalNotes .= "Final commission calculated based on approved amount: $" . number_format($claim->amount_approved, 2) . ". ";
                $approvalNotes .= "Commission rate: {$gs->influencer_commission_percentage}%. ";
                $approvalNotes .= "Final commission amount: $" . number_format($finalCommission, 2) . ". ";
                $approvalNotes .= "Approved by: " . auth()->user()->name . " (ID: " . auth()->id() . ").";

                // Update commission record
                $commission->update([
                    'commission_amount' => $finalCommission,
                    'status' => 'approved', // Changed from pending to approved
                    'commission_date' => now(),
                    'notes' => $approvalNotes,
                ]);

                \Log::info('Influencer commission approved', [
                    'commission_id' => $commission->id,
                    'claim_id' => $claim->id,
                    'estimated_commission' => $commission->estimated_commission,
                    'final_commission' => $finalCommission
                ]);

            } elseif ($newStatus === 'rejected') {
                // If claim is rejected, reject the commission
                $rejectionNotes = ($commission->notes ?? '') . "\n[" . now()->format('Y-m-d H:i:s') . "] Commission rejected: Claim was rejected by admin. ";
                $rejectionNotes .= "Rejected by: " . auth()->user()->name . " (ID: " . auth()->id() . ").";
                
                $commission->update([
                    'status' => 'rejected',
                    'commission_amount' => 0, // Set to 0 since claim was rejected
                    'notes' => $rejectionNotes,
                ]);

                \Log::info('Influencer commission rejected due to claim rejection', [
                    'commission_id' => $commission->id,
                    'claim_id' => $claim->id
                ]);
            }

        } catch (\Exception $e) {
            \Log::error('Failed to update influencer commission: ' . $e->getMessage(), [
                'claim_id' => $claim->id,
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
