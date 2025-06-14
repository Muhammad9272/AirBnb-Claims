<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Claim;
use App\Models\ClaimComment;
use App\Models\ClaimStatusHistory;
use App\Models\User;
use DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\NotificationService;

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
            // Update claim status
            $claim->status = $newStatus;
            
            // Update approved amount if status is approved
            if ($newStatus === 'approved') {
                $claim->amount_approved = $request->approved_amount;
                
                // Calculate commission if user has an active subscription
                $activeSubscription = $claim->user->activeuserSubscriptions()->first();
                if ($activeSubscription && $activeSubscription->plan) {
                    $commissionRate = $activeSubscription->plan->commission_percentage ?? 0;
                    $claim->commission_amount = ($request->approved_amount * $commissionRate) / 100;
                }
            }
            
            // Record rejection reason if status is rejected
            if ($newStatus === 'rejected') {
                $claim->rejection_reason = $request->rejection_reason;
            }
            
            $claim->save();
            
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
        
        // Send notification about new comment
        NotificationService::newComment($comment);
        
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
}
