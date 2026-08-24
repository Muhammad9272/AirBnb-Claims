<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Claim;
use App\Models\ClaimComment;
use App\Models\ClaimNote;
use App\Models\ClaimStatusHistory;
use App\Models\User;
use App\Models\GeneralSetting;
use App\Models\InfluencerCommission;
use App\Classes\GeniusMailer;
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
        $claim = Claim::with(['user', 'user.activeuserSubscriptions.plan', 'comments.user', 'evidence', 'statusHistory.user', 'notes.user', 'notes.editedByUser'])
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
            'status' => 'required|in:pending,under_review,additional_info_requested,challenge_1,challenge_2,approved,partial_payout,rejected',
            'approved_amount' => 'required_if:status,approved,partial_payout|nullable|numeric|min:0',
            'rejection_reason' => 'required_if:status,rejected|nullable|string',
            'comment' => 'nullable|string',
        ]);

        $claim = Claim::findOrFail($id);

        $result = (new \App\Services\ClaimStatusService())->applyStatusChange($claim, $request->status, [
            'approved_amount' => $request->approved_amount,
            'rejection_reason' => $request->rejection_reason,
            'comment' => $request->comment,
            'changed_by_id' => Auth::id(),
            'changed_by_label' => Auth::user()->name . ' (ID: ' . Auth::id() . ')',
        ]);

        if (!$result['success']) {
            return redirect()->back()->with('error', $result['error']);
        }

        return redirect()->back()->with('success', 'Claim status updated successfully.');
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
     * Add an internal note to a claim (admin only).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function addNote(Request $request, $id)
    {
        $request->validate([
            'note_content' => 'required|string|min:3'
        ]);
        
        $claim = Claim::findOrFail($id);
        
        ClaimNote::create([
            'claim_id' => $claim->id,
            'admin_user_id' => Auth::id(),
            'note_content' => $request->note_content
        ]);
        
        Log::info('Internal note added to claim', [
            'claim_id' => $id,
            'admin_id' => Auth::id()
        ]);
        
        return redirect()->back()->with('success', 'Internal note added successfully');
    }

    /**
     * Update an internal note (admin only).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $claimId
     * @param  int  $noteId
     * @return \Illuminate\Http\Response
     */
    public function updateNote(Request $request, $claimId, $noteId)
    {
        $request->validate([
            'note_content' => 'required|string|min:3'
        ]);
        
        $claim = Claim::findOrFail($claimId);
        $note = ClaimNote::where('claim_id', $claimId)->findOrFail($noteId);
        
        // Only allow the original creator or admins to edit
        if ($note->admin_user_id !== Auth::id() && Auth::user()->role_type !== 'admin') {
            return redirect()->back()->with('error', 'You can only edit your own notes');
        }
        
        $note->update([
            'note_content' => $request->note_content,
            'edited_by' => Auth::id(),
            'edited_at' => now()
        ]);
        
        Log::info('Internal note updated', [
            'claim_id' => $claimId,
            'note_id' => $noteId,
            'edited_by' => Auth::id()
        ]);
        
        return redirect()->back()->with('success', 'Internal note updated successfully');
    }

    /**
     * Delete an internal note (admin only).
     *
     * @param  int  $claimId
     * @param  int  $noteId
     * @return \Illuminate\Http\Response
     */
    public function deleteNote($claimId, $noteId)
    {
        $claim = Claim::findOrFail($claimId);
        $note = ClaimNote::where('claim_id', $claimId)->findOrFail($noteId);
        
        // Only allow the original creator or admins to delete
        if ($note->admin_user_id !== Auth::id() && Auth::user()->role_type !== 'admin') {
            return redirect()->back()->with('error', 'You can only delete your own notes');
        }
        
        $note->delete();
        
        Log::info('Internal note deleted', [
            'claim_id' => $claimId,
            'note_id' => $noteId,
            'deleted_by' => Auth::id()
        ]);
        
        return redirect()->back()->with('success', 'Internal note deleted successfully');
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
